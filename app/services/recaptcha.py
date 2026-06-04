import httpx

from app.core.config import get_settings


async def verify_recaptcha(token: str, action: str = "contact") -> bool:
    settings = get_settings()
    if not settings.recaptcha_secret_key:
        return settings.app_debug
    async with httpx.AsyncClient() as client:
        resp = await client.post(
            "https://www.google.com/recaptcha/api/siteverify",
            data={"secret": settings.recaptcha_secret_key, "response": token},
            timeout=10,
        )
        data = resp.json()
    return bool(data.get("success")) and float(data.get("score", 0)) >= settings.recaptcha_min_score
