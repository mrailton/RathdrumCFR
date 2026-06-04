from functools import lru_cache

from pydantic import Field
from pydantic_settings import BaseSettings, SettingsConfigDict


class Settings(BaseSettings):
    model_config = SettingsConfigDict(env_file=".env", env_file_encoding="utf-8", extra="ignore")

    app_name: str = "Rathdrum CFR"
    app_env: str = "local"
    app_debug: bool = False
    app_url: str = "http://127.0.0.1:8000"
    app_timezone: str = "Europe/Dublin"

    secret_key: str = Field(default="dev-insecure-change-me")
    database_url: str = "sqlite:///./rathdrumcfr.db"
    laravel_database_url: str | None = None

    session_lifetime: int = 120
    session_remember_lifetime: int = 43200
    session_secure_cookie: bool = False

    admin_email: str = "admin@example.com"
    mail_from: str = "noreply@example.com"
    smtp_host: str = "127.0.0.1"
    smtp_port: int = 1025
    smtp_user: str = ""
    smtp_password: str = ""
    smtp_tls: bool = False

    google_maps_api_key: str = ""
    recaptcha_site_key: str = ""
    recaptcha_secret_key: str = ""
    recaptcha_min_score: float = 0.5

    scheduler_enabled: bool = True

    def model_post_init(self, __context: object) -> None:
        if self.app_env == "testing":
            object.__setattr__(self, "scheduler_enabled", False)

    @property
    def is_mysql(self) -> bool:
        return self.database_url.startswith("mysql")

    @property
    def is_production(self) -> bool:
        return self.app_env == "production"

    @property
    def is_testing(self) -> bool:
        return self.app_env == "testing"


@lru_cache
def get_settings() -> Settings:
    settings = Settings()
    if settings.is_production and settings.secret_key == "dev-insecure-change-me":
        raise RuntimeError("SECRET_KEY must be set in production")
    if settings.is_production and not settings.is_mysql:
        raise RuntimeError("Production requires a MySQL DATABASE_URL")
    return settings
