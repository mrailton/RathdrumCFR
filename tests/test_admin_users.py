from app.models.user import User
from tests.conftest import extract_csrf


def test_admin_create_user(admin_client, db_session):
    page = admin_client.get("/admin/users/create")
    csrf = extract_csrf(page.text)

    response = admin_client.post(
        "/admin/users/create",
        data={
            "csrf_token": csrf,
            "name": "New Admin",
            "email": "newadmin@test.com",
            "password": "secure-pass",
            "role": "admin",
            "cfr_cert_expiry": "on",
        },
        follow_redirects=False,
    )
    assert response.status_code == 303

    from app.services.users import get_user_by_email

    created = get_user_by_email(db_session, "newadmin@test.com")
    assert created is not None
    assert created.role == "admin"
    assert created.reports is not None
    assert created.reports.cfr_cert_expiry is True


def test_admin_edit_user(admin_client, db_session, make_user):
    user = make_user(email="editme@test.com", role="user")

    page = admin_client.get(f"/admin/users/{user.id}/edit")
    csrf = extract_csrf(page.text)
    response = admin_client.post(
        f"/admin/users/{user.id}/edit",
        data={
            "csrf_token": csrf,
            "name": "Edited Name",
            "email": user.email,
            "role": "admin",
        },
        follow_redirects=False,
    )
    assert response.status_code == 303

    updated = db_session.get(User, user.id)
    assert updated.name == "Edited Name"
    assert updated.role == "admin"
