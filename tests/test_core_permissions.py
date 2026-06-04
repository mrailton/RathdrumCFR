from app.core.permissions import get_role_names, is_admin, is_operator


def test_permissions_shim_matches_policies(db_session, make_user):
    user = make_user(role="admin")
    assert is_admin(db_session, user)
    assert is_operator(db_session, user)
    assert get_role_names(db_session, user) == ["admin"]
