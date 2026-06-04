from sqlalchemy import select
from sqlalchemy.orm import Session, selectinload

from app.core.time import utc_now
from app.models.defib import Defib
from app.models.defib_inspection import DefibInspection
from app.models.defib_note import DefibNote


def list_defibs(db: Session, *, include_trashed: bool = False) -> list[Defib]:
    q = select(Defib).order_by(Defib.name)
    if not include_trashed:
        q = q.where(Defib.deleted_at.is_(None))
    return list(db.scalars(q).all())


def list_public_defibs(db: Session) -> list[Defib]:
    return list(
        db.scalars(
            select(Defib).where(
                Defib.display_on_map.is_(True), Defib.deleted_at.is_(None), Defib.coordinates.isnot(None)
            )
        ).all()
    )


def get_defib(db: Session, defib_id: int) -> Defib | None:
    return db.scalars(
        select(Defib).where(Defib.id == defib_id).options(selectinload(Defib.inspections), selectinload(Defib.notes))
    ).first()


def create_defib(db: Session, data: dict, creator_id: int) -> Defib:
    row = Defib(user_id=creator_id, **data)
    db.add(row)
    db.flush()
    return row


def update_defib(db: Session, defib: Defib, data: dict) -> Defib:
    for key, value in data.items():
        if hasattr(defib, key):
            setattr(defib, key, value)
    return defib


def soft_delete_defib(db: Session, defib: Defib) -> None:
    defib.deleted_at = utc_now()


def restore_defib(db: Session, defib: Defib) -> None:
    defib.deleted_at = None


def add_defib_note(db: Session, defib_id: int, note: str, user_id: int) -> DefibNote:
    row = DefibNote(defib_id=defib_id, user_id=user_id, note=note)
    db.add(row)
    db.flush()
    return row


def create_inspection(db: Session, defib: Defib, data: dict, creator_id: int) -> DefibInspection:
    inspection = DefibInspection(user_id=creator_id, defib_id=defib.id, **data)
    db.add(inspection)
    db.flush()
    member = inspection.member
    defib.pads_expire_at = inspection.pads_expire_at
    defib.battery_expires_at = inspection.battery_expires_at
    defib.last_inspected_at = inspection.inspected_at
    defib.last_inspected_by = member.name if member else None
    return inspection
