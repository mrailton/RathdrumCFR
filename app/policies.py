"""Simple role-based access: admin (full) and user (callouts + defibs, no delete)."""

from __future__ import annotations

from sqlalchemy.orm import Session

from app.models.user import User

ROLE_ADMIN = "admin"
ROLE_USER = "user"


class AuthorizationError(Exception):
    pass


def normalize_role(role_name: str | None) -> str:
    if role_name == ROLE_ADMIN:
        return ROLE_ADMIN
    return ROLE_USER


def get_role_names(_db: Session, user: User) -> list[str]:
    if not user.role:
        return []
    return [user.role]


def is_admin(_db: Session, user: User | None) -> bool:
    if user is None or not user.role:
        return False
    return user.role == ROLE_ADMIN


def is_operator(_db: Session, user: User | None) -> bool:
    """May use the admin panel (callouts/defibs at minimum)."""
    if user is None or not user.role:
        return False
    if is_admin(_db, user):
        return True
    return user.role == ROLE_USER


def can_manage_callouts(db: Session, user: User | None) -> bool:
    return is_operator(db, user)


def can_delete_callouts(db: Session, user: User | None) -> bool:
    return is_admin(db, user)


def can_manage_defibs(db: Session, user: User | None) -> bool:
    return is_operator(db, user)


def can_delete_defibs(db: Session, user: User | None) -> bool:
    return is_admin(db, user)


def authorize_admin(db: Session, user: User | None) -> None:
    if not is_admin(db, user):
        raise AuthorizationError("Admin role required")


def authorize_operator(db: Session, user: User | None) -> None:
    if not is_operator(db, user):
        raise AuthorizationError("User or admin role required")
