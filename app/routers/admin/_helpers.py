from fastapi import HTTPException
from sqlalchemy.orm import Session
from starlette.datastructures import UploadFile

from app import policies
from app.models.user import User

FormValue = str | UploadFile | None


def require_admin(db: Session, user: User) -> None:
    if not policies.is_admin(db, user):
        raise HTTPException(status_code=403, detail="Admin access required")


def require_operator(db: Session, user: User) -> None:
    if not policies.is_operator(db, user):
        raise HTTPException(status_code=403, detail="Forbidden")


def form_str(value: FormValue, default: str = "") -> str:
    return value if isinstance(value, str) else default


def form_str_optional(value: FormValue) -> str | None:
    return value if isinstance(value, str) else None


def parse_bool(value: FormValue) -> bool:
    return isinstance(value, str) and value in ("1", "true", "on", "yes", "True")
