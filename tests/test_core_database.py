import pytest
from sqlalchemy.orm import sessionmaker

from app.core import database as db_module
from app.core.database import get_db, mark_for_commit, should_commit


def _bind_test_engine(engine):
    db_module._engine = engine
    db_module._SessionLocal = sessionmaker(bind=engine, autoflush=False, autocommit=False)


def test_get_db_commits_when_marked(engine):
    _bind_test_engine(engine)

    gen = get_db()
    session = next(gen)
    mark_for_commit(session)
    with pytest.raises(StopIteration):
        next(gen)

    factory = db_module.get_session_factory()
    check = factory()
    try:
        assert check.bind is session.bind
    finally:
        check.close()


def test_get_db_rolls_back_without_mark(engine):
    _bind_test_engine(engine)

    gen = get_db()
    session = next(gen)
    session.add(__import__("app.models.user", fromlist=["User"]).User(
        name="Rollback",
        email="rollback@test.com",
        password="x",
        role="user",
    ))
    with pytest.raises(StopIteration):
        next(gen)

    factory = db_module.get_session_factory()
    check = factory()
    try:
        from app.services.users import get_user_by_email

        assert get_user_by_email(check, "rollback@test.com") is None
    finally:
        check.close()


def test_should_commit_default_false(engine):
    _bind_test_engine(engine)
    session = db_module.get_session_factory()()
    try:
        assert should_commit(session) is False
        mark_for_commit(session)
        assert should_commit(session) is True
    finally:
        session.close()
