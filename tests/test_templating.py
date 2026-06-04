from app.templating import flash, register_route_names, route_is, url_for


def test_url_for_known_route():
    class Route:
        name = "health.check"
        path = "/health"

    register_route_names([Route()])
    assert url_for("health.check") == "/health"


def test_route_is_admin_dashboard():
    from starlette.requests import Request

    scope = {
        "type": "http",
        "method": "GET",
        "path": "/admin",
        "headers": [],
        "route": type("R", (), {"name": "admin.dashboard"})(),
    }
    request = Request(scope)
    assert route_is(request, "admin.dashboard") is True


def test_url_for_unknown_route_raises():
    import pytest

    with pytest.raises(ValueError, match="Unknown route"):
        url_for("nonexistent.route")


def test_url_for_with_params():
    class Route:
        name = "admin.members.edit"
        path = "/admin/members/{member_id}/edit"

    register_route_names([Route()])
    assert url_for("admin.members.edit", member_id=5) == "/admin/members/5/edit"


def test_route_is_wildcard_pattern():
    from starlette.requests import Request

    scope = {
        "type": "http",
        "method": "GET",
        "path": "/admin/members/1",
        "headers": [],
        "route": type("R", (), {"name": "admin.members.show"})(),
    }
    request = Request(scope)
    assert route_is(request, "admin.members.*") is True


def test_render_with_flash_and_validation_errors():
    from starlette.requests import Request
    from starlette.datastructures import Headers

    scope = {
        "type": "http",
        "method": "GET",
        "path": "/",
        "headers": [],
        "session": {"flash_success": "Done", "validation_errors": {"email": "bad"}},
    }
    request = Request(scope)
    response = __import__("app.templating", fromlist=["render"]).render(request, "auth/login.html")
    assert response.status_code == 200


def test_flash_stores_message():
    from starlette.requests import Request

    scope = {"type": "http", "method": "GET", "path": "/", "headers": [], "session": {}}
    request = Request(scope)
    flash(request, "success", "Saved")
    assert request.session["flash_success"] == "Saved"
