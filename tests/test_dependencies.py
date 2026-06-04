import pytest
from fastapi import HTTPException

from app.dependencies import LoginRequired, get_csrf_token, require_auth, verify_csrf


def test_csrf_token_roundtrip():
    from starlette.requests import Request

    scope = {
        "type": "http",
        "method": "GET",
        "path": "/",
        "headers": [],
        "session": {},
    }
    request = Request(scope)
    token = get_csrf_token(request)
    assert token
    verify_csrf(request, token)


def test_verify_csrf_rejects_mismatch():
    from starlette.requests import Request

    scope = {"type": "http", "method": "GET", "path": "/", "headers": [], "session": {"csrf_token": "a"}}
    request = Request(scope)
    with pytest.raises(HTTPException) as exc:
        verify_csrf(request, "b")
    assert exc.value.status_code == 403


def test_require_auth_raises_login_required():
    with pytest.raises(LoginRequired):
        require_auth(None)
