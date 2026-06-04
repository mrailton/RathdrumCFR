import pytest

from app.policies import (
    ROLE_ADMIN,
    ROLE_USER,
    AuthorizationError,
    authorize_admin,
    authorize_operator,
    can_delete_callouts,
    can_delete_defibs,
    can_manage_callouts,
    get_role_names,
    is_admin,
    is_operator,
    normalize_role,
)


def test_get_role_names_empty(db_session, make_user):
    user = make_user()
    user.role = ""
    db_session.flush()
    assert get_role_names(db_session, user) == []


def test_is_admin_without_role(db_session, make_user):
    user = make_user()
    user.role = ""
    db_session.flush()
    assert not is_admin(db_session, user)


def test_normalize_role():
    assert normalize_role("admin") == ROLE_ADMIN
    assert normalize_role("user") == ROLE_USER
    assert normalize_role("super_admin") == ROLE_USER
    assert normalize_role(None) == ROLE_USER


def test_is_admin_and_operator(db_session, make_user):
    admin = make_user(email="a@test.com", role=ROLE_ADMIN)
    operator = make_user(email="o@test.com", role=ROLE_USER)
    no_role = make_user(email="n@test.com")
    no_role.role = ""
    db_session.flush()

    assert is_admin(db_session, admin)
    assert is_operator(db_session, admin)
    assert not is_admin(db_session, operator)
    assert is_operator(db_session, operator)
    assert not is_operator(db_session, no_role)
    assert get_role_names(db_session, admin) == [ROLE_ADMIN]


def test_can_permissions(db_session, make_user):
    admin = make_user(email="perm-admin@test.com", role=ROLE_ADMIN)
    operator = make_user(email="perm-op@test.com", role=ROLE_USER)

    assert can_manage_callouts(db_session, operator)
    assert not can_delete_callouts(db_session, operator)
    assert can_delete_callouts(db_session, admin)
    assert can_delete_defibs(db_session, admin)
    assert not can_delete_defibs(db_session, operator)


def test_authorize_raises(db_session, make_user):
    operator = make_user(email="auth-op@test.com", role=ROLE_USER)

    with pytest.raises(AuthorizationError):
        authorize_admin(db_session, operator)

    authorize_operator(db_session, operator)

    with pytest.raises(AuthorizationError):
        authorize_operator(db_session, None)
