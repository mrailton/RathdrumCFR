from app.policies import ROLE_ADMIN, ROLE_USER, is_admin, is_operator


def test_user_role_access(db_session):
    from app.core.security import hash_password
    from app.models.user import User

    user = User(name="Test", email="roles-user@test.com", password=hash_password("x"), role=ROLE_USER)
    db_session.add(user)
    db_session.flush()
    assert is_operator(db_session, user)
    assert not is_admin(db_session, user)


def test_admin_role_access(db_session):
    from app.core.security import hash_password
    from app.models.user import User

    user = User(name="Admin", email="roles-admin@test.com", password=hash_password("x"), role=ROLE_ADMIN)
    db_session.add(user)
    db_session.flush()
    assert is_operator(db_session, user)
    assert is_admin(db_session, user)
