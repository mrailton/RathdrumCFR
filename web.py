"""ASGI entrypoint for uvicorn and production servers."""

from app.main import app

__all__ = ["app"]
