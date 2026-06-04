from sqlalchemy import select
from sqlalchemy.orm import Session

from app.models.ampds_code import AMPDSCode


def list_ampds_codes(db: Session) -> list[AMPDSCode]:
    return list(db.scalars(select(AMPDSCode).order_by(AMPDSCode.code)).all())


def save_ampds_code(db: Session, data: dict, code_id: int | None = None) -> AMPDSCode:
    if code_id:
        row = db.get(AMPDSCode, code_id)
        if not row:
            raise ValueError("AMPDS code not found")
        for k, v in data.items():
            setattr(row, k, v)
        return row
    row = AMPDSCode(**data)
    db.add(row)
    db.flush()
    return row


def delete_ampds_code(db: Session, code: AMPDSCode) -> None:
    db.delete(code)
