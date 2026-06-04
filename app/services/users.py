from sqlalchemy import MetaData, Table, select
from sqlalchemy.engine import Engine
from sqlalchemy.orm import Session, selectinload

from app.core.security import hash_password, verify_password
from app.models.user import User
from app.models.user_report import UserReport
from app.policies import ROLE_ADMIN, ROLE_USER, normalize_role

LARAVEL_USER_MODEL_TYPE = "App\\Models\\User"


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


def apply_laravel_roles_from_source(source: Engine, db: Session) -> int:
    """Map legacy Spatie roles onto users.role (not imported as tables)."""
    inspector = __import__("sqlalchemy").inspect(source)
    if "model_has_roles" not in inspector.get_table_names() or "roles" not in inspector.get_table_names():
        return 0

    src_meta = MetaData()
    roles_table = Table("roles", src_meta, autoload_with=source)
    mhr_table = Table("model_has_roles", src_meta, autoload_with=source)

    rows = (
        source.connect()
        .execute(
            select(mhr_table.c.model_id, roles_table.c.name)
            .select_from(mhr_table.join(roles_table, roles_table.c.id == mhr_table.c.role_id))
            .where(mhr_table.c.model_type == LARAVEL_USER_MODEL_TYPE)
        )
        .all()
    )

    updated = 0
    for user_id, role_name in rows:
        user = db.get(User, user_id)
        if user:
            user.role = normalize_role(role_name)
            updated += 1
    return updated
