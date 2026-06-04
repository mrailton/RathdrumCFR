import asyncio
from unittest.mock import AsyncMock, MagicMock, patch

from app.services import recaptcha as recaptcha_service


def test_verify_recaptcha_skipped_when_no_secret(monkeypatch):
    monkeypatch.setenv("RECAPTCHA_SECRET_KEY", "")
    monkeypatch.setenv("APP_DEBUG", "false")
    from app.core.config import get_settings

    get_settings.cache_clear()
    try:
        assert asyncio.run(recaptcha_service.verify_recaptcha("token")) is False
    finally:
        get_settings.cache_clear()


@patch("app.services.recaptcha.httpx.AsyncClient")
def test_verify_recaptcha_success(mock_client_cls, monkeypatch):
    monkeypatch.setenv("RECAPTCHA_SECRET_KEY", "secret")
    monkeypatch.setenv("APP_DEBUG", "false")
    from app.core.config import get_settings

    get_settings.cache_clear()
    try:
        mock_resp = MagicMock()
        mock_resp.json.return_value = {"success": True, "score": 0.9}
        mock_client = AsyncMock()
        mock_client.__aenter__.return_value = mock_client
        mock_client.post.return_value = mock_resp
        mock_client_cls.return_value = mock_client

        assert asyncio.run(recaptcha_service.verify_recaptcha("good-token")) is True
    finally:
        get_settings.cache_clear()
