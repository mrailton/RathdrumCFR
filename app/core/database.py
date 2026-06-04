from collections.abc import Generator
from typing import Any

from sqlalchemy import create_engine
from sqlalchemy.orm import Session, sessionmaker

from app.core.config import Settings, get_settings

_engine = None
_SessionLocal: sessionmaker[Session] | None = None


def create_engine_for_settings(settings: Settings):
    connect_args: dict[str, Any] = {}
    if settings.database_url.startswith("sqlite"):
        connect_args["check_same_thread"] = False
    return create_engine(
        settings.database_url,
        pool_pre_ping=True,
        connect_args=connect_args,
    )


def init_db(settings: Settings | None = None) -> None:
    global _engine, _SessionLocal
    settings = settings or get_settings()
    _engine = create_engine_for_settings(settings)
    _SessionLocal = sessionmaker(bind=_engine, autoflush=False, autocommit=False)


def get_engine():
    if _engine is None:
        init_db()
    return _engine


def get_session_factory() -> sessionmaker[Session]:
    if _SessionLocal is None:
        init_db()
    assert _SessionLocal is not None
    return _SessionLocal


def get_db() -> Generator[Session]:
    session = get_session_factory()()
    commit = False
    try:
        yield session
        commit = bool(session.info.get("commit"))
        if commit:
            session.commit()
        else:
            session.rollback()
    except Exception:
        session.rollback()
        raise
    finally:
        session.close()


def mark_for_commit(session: Session) -> None:
    session.info["commit"] = True


def should_commit(session: Session) -> bool:
    return bool(session.info.get("commit"))
