import os
import re

import pytest
from fastapi.testclient import TestClient
from sqlalchemy import create_engine
from sqlalchemy.orm import sessionmaker
from sqlalchemy.pool import StaticPool

os.environ.setdefault("APP_ENV", "testing")
os.environ.setdefault("DATABASE_URL", "sqlite://")
os.environ.setdefault("SECRET_KEY", "test-secret")

from app.core import security as security_module
from app.core.database import get_db, should_commit
from app.main import app
from app.models.base import Base
from app.models.user import User

TEST_PASSWORD = "test-password"
_FAST_HASH_PREFIX = "$pytest$"
_real_verify_password = security_module.verify_password


def _fast_hash_password(password: str) -> str:
    return f"{_FAST_HASH_PREFIX}{password}"


def _fast_verify_password(plain: str, hashed: str) -> bool:
    if hashed.startswith(_FAST_HASH_PREFIX):
        return hashed == f"{_FAST_HASH_PREFIX}{plain}"
    return _real_verify_password(plain, hashed)


def extract_csrf(html: str) -> str:
    match = re.search(r'name="csrf_token" value="([^"]+)"', html)
    assert match, "csrf_token not found in HTML"
    return match.group(1)


def login_client(client: TestClient, email: str, password: str = TEST_PASSWORD) -> None:
    response = client.get("/admin/login")
    csrf = extract_csrf(response.text)
    client.post(
        "/admin/login",
        data={"email": email, "password": password, "csrf_token": csrf},
        follow_redirects=False,
    )


@pytest.fixture(autouse=True)
def _fast_password_hashing(request, monkeypatch):
    """Avoid bcrypt cost in tests; test_security.py opts out via marker."""
    if request.node.get_closest_marker("real_password_hash"):
        return
    monkeypatch.setattr("app.core.security.hash_password", _fast_hash_password)
    monkeypatch.setattr("app.core.security.verify_password", _fast_verify_password)
    monkeypatch.setattr("app.services.users.hash_password", _fast_hash_password)
    monkeypatch.setattr("app.services.users.verify_password", _fast_verify_password)


@pytest.fixture(scope="session")
def engine():
    test_engine = create_engine(
        "sqlite://",
        connect_args={"check_same_thread": False},
        poolclass=StaticPool,
    )
    Base.metadata.create_all(bind=test_engine)
    yield test_engine
    test_engine.dispose()


@pytest.fixture(scope="session", autouse=True)
def _close_app_database_engine():
    """Dispose the engine created by init_db() when app.main is imported."""
    yield
    from app.core import database as db_module

    if db_module._engine is not None:
        db_module._engine.dispose()
        db_module._engine = None
        db_module._SessionLocal = None


@pytest.fixture
def db_session(engine):
    Session = sessionmaker(bind=engine, expire_on_commit=False)
    session = Session()
    try:
        yield session
    finally:
        session.rollback()
        for table in reversed(Base.metadata.sorted_tables):
            session.execute(table.delete())
        session.commit()
        session.close()


@pytest.fixture(scope="session")
def http_client():
    """One TestClient for the suite — avoids ~0.45s ASGI lifespan per HTTP test."""
    with TestClient(app) as test_client:
        yield test_client


@pytest.fixture
def client(db_session, http_client):
    http_client.cookies.clear()

    def override_get_db():
        try:
            yield db_session
            if should_commit(db_session):
                db_session.commit()
                db_session.info.pop("commit", None)
        except Exception:
            db_session.rollback()
            raise

    app.dependency_overrides[get_db] = override_get_db
    yield http_client
    app.dependency_overrides.clear()
    http_client.cookies.clear()


@pytest.fixture
def make_user(db_session):
    counter = {"n": 0}

    def factory(
        *,
        email: str | None = None,
        role: str = "user",
        name: str = "Test User",
        password: str = TEST_PASSWORD,
    ) -> User:
        counter["n"] += 1
        address = email or f"user{counter['n']}@test.com"
        user = User(
            name=name,
            email=address,
            password=security_module.hash_password(password),
            role=role,
        )
        db_session.add(user)
        db_session.flush()
        return user

    return factory


@pytest.fixture
def admin_user(make_user):
    return make_user(role="admin", name="Admin User")


@pytest.fixture
def operator_user(make_user):
    return make_user(role="user", name="Operator User")


@pytest.fixture
def admin_client(client, admin_user):
    login_client(client, admin_user.email)
    return client


@pytest.fixture
def operator_client(client, operator_user):
    login_client(client, operator_user.email)
    return client


@pytest.fixture
def cli_db(db_session, monkeypatch):
    """Route CLI commands through the per-test database session."""

    class _SessionFactory:
        def __call__(self):
            return db_session

    factory = _SessionFactory()
    monkeypatch.setattr("app.cli._session", lambda: db_session)
    monkeypatch.setattr("app.cli.get_session_factory", lambda: factory)
    monkeypatch.setattr("app.core.scheduler.get_session_factory", lambda: factory)
