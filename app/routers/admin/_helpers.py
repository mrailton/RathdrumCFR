from fastapi import HTTPException
from sqlalchemy.orm import Session

from app import policies
from app.models.user import User


def require_admin(db: Session, user: User) -> None:
    if not policies.is_admin(db, user):
        raise HTTPException(status_code=403, detail="Admin access required")


def require_operator(db: Session, user: User) -> None:
    if not policies.is_operator(db, user):
        raise HTTPException(status_code=403, detail="Forbidden")


def parse_bool(value: str | None) -> bool:
    return value in ("1", "true", "on", "yes", "True")
