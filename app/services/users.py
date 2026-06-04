from sqlalchemy import select
from sqlalchemy.orm import Session, selectinload

from app.core.security import hash_password, verify_password
from app.models.user import User
from app.models.user_report import UserReport
from app.policies import ROLE_ADMIN, ROLE_USER, normalize_role


def get_user_by_id(db: Session, user_id: int) -> User | None:
    return db.scalars(
        select(User).where(User.id == user_id, User.deleted_at.is_(None)).options(selectinload(User.reports))
    ).first()


def get_user_by_email(db: Session, email: str) -> User | None:
    return db.scalars(select(User).where(User.email == email, User.deleted_at.is_(None))).first()


def authenticate(db: Session, email: str, password: str) -> User | None:
    user = get_user_by_email(db, email)
    if not user or not verify_password(password, user.password):
        return None
    return user


def list_users(db: Session) -> list[User]:
    return list(db.scalars(select(User).where(User.deleted_at.is_(None)).order_by(User.name)).all())


def create_user(
    db: Session,
    *,
    name: str,
    email: str,
    password: str,
    creator_id: int,
    role: str = ROLE_USER,
) -> User:
    user = User(name=name, email=email, password=hash_password(password), role=normalize_role(role))
    db.add(user)
    db.flush()
    return user


def update_user(db: Session, user: User, data: dict) -> User:
    for key, value in data.items():
        if key == "password" and value:
            user.password = hash_password(value)
        elif key == "role" and value:
            user.role = normalize_role(str(value))
        elif hasattr(user, key) and key != "password":
            setattr(user, key, value)
    return user


def set_user_role(user: User, role_name: str) -> None:
    if role_name not in (ROLE_ADMIN, ROLE_USER):
        raise ValueError(f"Unknown role: {role_name}")
    user.role = normalize_role(role_name)


def get_user_role_name(user: User) -> str:
    return normalize_role(user.role)


def get_or_create_user_report(db: Session, user: User) -> UserReport:
    if user.reports:
        return user.reports
    report = UserReport(user_id=user.id)
    db.add(report)
    db.flush()
    return report


def update_user_report_flags(db: Session, user: User, flags: dict[str, bool]) -> UserReport:
    report = get_or_create_user_report(db, user)
    for key, value in flags.items():
        if hasattr(report, key):
            setattr(report, key, value)
    return report
