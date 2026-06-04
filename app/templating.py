from pathlib import Path

from fastapi import Request
from fastapi.templating import Jinja2Templates

from app import policies
from app.dependencies import get_csrf_token

TEMPLATES_DIR = Path(__file__).parent / "templates"
templates = Jinja2Templates(directory=str(TEMPLATES_DIR))

_route_names: dict[str, str] = {}


def register_route_names(routes: list) -> None:
    for route in routes:
        name = getattr(route, "name", None)
        path = getattr(route, "path", None)
        if name and path:
            _route_names[name] = path


def url_for(name: str, **path_params: object) -> str:
    path = _route_names.get(name)
    if path is None:
        raise ValueError(f"Unknown route name: {name}")
    for key, value in path_params.items():
        placeholder = "{" + key + "}"
        if placeholder in path:
            path = path.replace(placeholder, str(value))
    return path


def route_is(request: Request, pattern: str) -> bool:
    path = request.url.path
    name = getattr(request.scope.get("route"), "name", "") or ""
    if pattern == "admin.dashboard":
        return path == "/admin"
    if pattern.endswith(".*"):
        base = pattern[:-2]
        if base.startswith("admin."):
            base = base[len("admin.") :]
        prefix = "/admin/" + base.replace(".", "/")
        return path.startswith(prefix)
    return name == pattern or name.startswith(pattern.replace(".*", ""))


def setup_template_globals() -> None:
    templates.env.globals.update({"url_for": url_for, "route_is": route_is})


def flash(request: Request, category: str, message: str) -> None:
    request.session[f"flash_{category}"] = message


def _access_context(db, user) -> dict:
    if db is None or user is None:
        return {
            "is_admin": False,
            "can_manage_callouts": False,
            "can_delete_callouts": False,
            "can_manage_defibs": False,
            "can_delete_defibs": False,
        }
    return {
        "is_admin": policies.is_admin(db, user),
        "can_manage_callouts": policies.can_manage_callouts(db, user),
        "can_delete_callouts": policies.can_delete_callouts(db, user),
        "can_manage_defibs": policies.can_manage_defibs(db, user),
        "can_delete_defibs": policies.can_delete_defibs(db, user),
    }


def render(
    request: Request,
    name: str,
    context: dict | None = None,
    user=None,
    db=None,
    status_code: int = 200,
):
    ctx: dict = {
        "request": request,
        "csrf_token": get_csrf_token(request),
        "user": user,
        "errors": {},
        **_access_context(db, user),
    }
    for key in ("success", "error"):
        if key in request.session:
            ctx[key] = request.session.pop(f"flash_{key}", None)
    if "validation_errors" in request.session:
        ctx["errors"] = request.session.pop("validation_errors")
    if context:
        ctx.update(context)
    return templates.TemplateResponse(request, name, ctx, status_code=status_code)
