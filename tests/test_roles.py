from app.policies import ROLE_ADMIN, ROLE_USER, is_admin, is_operator
from app.services import users as user_service


def test_user_role_access(db_session):
    from app.core.security import hash_password
    from app.models.user import User

    user = User(name="Test", email="user@test.com", password=hash_password("x"), role=ROLE_USER)
    db_session.add(user)
    db_session.flush()
    assert is_operator(db_session, user)
    assert not is_admin(db_session, user)


def test_admin_role_access(db_session):
    from app.core.security import hash_password
    from app.models.user import User

    user = User(name="Admin", email="admin@test.com", password=hash_password("x"), role=ROLE_ADMIN)
    db_session.add(user)
    db_session.flush()
    assert is_operator(db_session, user)
    assert is_admin(db_session, user)


def test_normalize_super_admin(db_session):
    from app.core.security import hash_password
    from app.models.user import User

    user = User(name="Legacy", email="legacy@test.com", password=hash_password("x"), role="super_admin")
    db_session.add(user)
    db_session.flush()
    assert is_admin(db_session, user)
    assert user_service.get_user_role_name(user) == ROLE_ADMIN
