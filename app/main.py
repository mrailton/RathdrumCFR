from contextlib import asynccontextmanager
from pathlib import Path

from fastapi import FastAPI, Request
from fastapi.responses import RedirectResponse
from fastapi.staticfiles import StaticFiles
from starlette.middleware.sessions import SessionMiddleware

from app.core.config import get_settings
from app.core.database import init_db
from app.dependencies import LoginRequired
from app.routers import api_router
from app.templating import register_route_names, setup_template_globals

settings = get_settings()
init_db(settings)


@asynccontextmanager
async def lifespan(app: FastAPI):
    from app.core.scheduler import start_scheduler, stop_scheduler

    register_route_names(list(app.routes))
    start_scheduler(settings)
    try:
        yield
    finally:
        stop_scheduler()


app = FastAPI(title=settings.app_name, lifespan=lifespan)
app.add_middleware(
    SessionMiddleware,
    secret_key=settings.secret_key,
    max_age=settings.session_lifetime * 60,
    https_only=settings.session_secure_cookie,
)

static_dir = Path(__file__).resolve().parent / "static" / "dist"
if static_dir.exists():
    app.mount("/static", StaticFiles(directory=str(static_dir)), name="static")

setup_template_globals()
app.include_router(api_router)


@app.exception_handler(LoginRequired)
async def login_required_handler(_request: Request, _exc: LoginRequired):
    return RedirectResponse(url="/admin/login", status_code=303)
