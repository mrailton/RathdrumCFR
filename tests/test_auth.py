from app.core import security as security_module
from app.models.user import User
from tests.conftest import TEST_PASSWORD, extract_csrf


def test_login_page(client):
    response = client.get("/admin/login")
    assert response.status_code == 200
    assert "Admin login" in response.text
    assert extract_csrf(response.text)


def test_login_with_remember_me(client, admin_user, db_session):
    page = client.get("/admin/login")
    csrf = extract_csrf(page.text)
    client.post(
        "/admin/login",
        data={
            "email": admin_user.email,
            "password": TEST_PASSWORD,
            "csrf_token": csrf,
            "remember_me": "on",
        },
        follow_redirects=False,
    )
    db_session.refresh(admin_user)
    assert admin_user.last_login_at is not None


def test_login_success_redirects_to_admin(client, admin_user):
    page = client.get("/admin/login")
    csrf = extract_csrf(page.text)
    response = client.post(
        "/admin/login",
        data={"email": admin_user.email, "password": TEST_PASSWORD, "csrf_token": csrf},
        follow_redirects=False,
    )
    assert response.status_code == 303
    assert response.headers["location"] == "/admin"

    dashboard = client.get("/admin/", follow_redirects=True)
    assert dashboard.status_code == 200


def test_login_invalid_credentials(client, admin_user):
    page = client.get("/admin/login")
    csrf = extract_csrf(page.text)
    response = client.post(
        "/admin/login",
        data={"email": admin_user.email, "password": "wrong", "csrf_token": csrf},
    )
    assert response.status_code == 422
    assert "Invalid credentials" in response.text


def test_login_without_role_rejected(client, db_session):
    user = User(
        name="No Role",
        email="norole@test.com",
        password=security_module.hash_password(TEST_PASSWORD),
        role="",
    )
    db_session.add(user)
    db_session.flush()

    page = client.get("/admin/login")
    csrf = extract_csrf(page.text)
    response = client.post(
        "/admin/login",
        data={"email": user.email, "password": TEST_PASSWORD, "csrf_token": csrf},
    )
    assert response.status_code == 422
    assert "no assigned role" in response.text


def test_admin_requires_login(client):
    response = client.get("/admin/", follow_redirects=False)
    assert response.status_code == 303
    assert response.headers["location"] == "/admin/login"


def test_logout(admin_client):
    page = admin_client.get("/admin/", follow_redirects=True)
    csrf = extract_csrf(page.text)
    response = admin_client.post("/admin/logout", data={"csrf_token": csrf}, follow_redirects=False)
    assert response.status_code == 303
    assert response.headers["location"] == "/admin/login"

    protected = admin_client.get("/admin/", follow_redirects=False)
    assert protected.status_code == 303
    assert protected.headers["location"] == "/admin/login"


def test_logged_in_user_redirected_from_login(admin_client):
    response = admin_client.get("/admin/login", follow_redirects=False)
    assert response.status_code == 303
    assert response.headers["location"] == "/admin"
