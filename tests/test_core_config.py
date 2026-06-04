import pytest

from app.core.config import Settings, get_settings


def test_testing_disables_scheduler():
    settings = Settings(app_env="testing")
    assert settings.scheduler_enabled is False
    assert settings.is_testing is True


def test_is_mysql_detection():
    settings = Settings(database_url="mysql+pymysql://localhost/db")
    assert settings.is_mysql is True


def test_production_requires_secret(monkeypatch):
    monkeypatch.setenv("APP_ENV", "production")
    monkeypatch.setenv("SECRET_KEY", "dev-insecure-change-me")
    monkeypatch.setenv("DATABASE_URL", "mysql+pymysql://localhost/db")
    get_settings.cache_clear()
    try:
        with pytest.raises(RuntimeError, match="SECRET_KEY"):
            get_settings()
    finally:
        get_settings.cache_clear()


def test_production_requires_mysql(monkeypatch):
    monkeypatch.setenv("APP_ENV", "production")
    monkeypatch.setenv("SECRET_KEY", "a" * 32)
    monkeypatch.setenv("DATABASE_URL", "sqlite:///tmp.db")
    get_settings.cache_clear()
    try:
        with pytest.raises(RuntimeError, match="MySQL"):
            get_settings()
    finally:
        get_settings.cache_clear()
