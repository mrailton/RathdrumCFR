import pytest

from app.core import security as security_module
from app.policies import ROLE_ADMIN, ROLE_USER
from app.services import users as user_service
from tests.conftest import TEST_PASSWORD


def test_authenticate_success(db_session, make_user):
    user = make_user(email="auth@test.com", password=TEST_PASSWORD)
    assert user_service.authenticate(db_session, user.email, TEST_PASSWORD) == user
    assert user_service.authenticate(db_session, user.email, "wrong") is None


def test_create_and_update_user(db_session, admin_user):
    created = user_service.create_user(
        db_session,
        name="New User",
        email="new@test.com",
        password="secret123",
        creator_id=admin_user.id,
        role=ROLE_USER,
    )
    assert created.role == ROLE_USER
    assert security_module.verify_password("secret123", created.password)

    user_service.update_user(
        db_session,
        created,
        {"name": "Updated", "role": ROLE_ADMIN, "password": "newpass"},
    )
    assert created.name == "Updated"
    assert created.role == ROLE_ADMIN
    assert security_module.verify_password("newpass", created.password)


def test_set_user_role(db_session, make_user):
    user = make_user(role="user")
    user_service.set_user_role(user, "admin")
    assert user.role == "admin"


def test_set_user_role_invalid():
    from app.models.user import User

    user = User(name="X", email="x@test.com", password=security_module.hash_password("x"), role=ROLE_USER)
    with pytest.raises(ValueError, match="Unknown role"):
        user_service.set_user_role(user, "invalid")


def test_user_report_flags(db_session, make_user):
    user = make_user(email="reports@test.com")
    report = user_service.update_user_report_flags(
        db_session,
        user,
        {"cfr_cert_expiry": True, "defib_inspection": False},
    )
    assert report.cfr_cert_expiry is True
    assert report.defib_inspection is False

    db_session.refresh(user)
    again = user_service.get_or_create_user_report(db_session, user)
    assert again.id == report.id
