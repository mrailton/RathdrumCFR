from sqlalchemy import select
from sqlalchemy.orm import Session, selectinload

from app.models.callout import Callout
from app.models.member import Member


def list_callouts(db: Session, *, date_from=None, date_to=None) -> list[Callout]:
    q = select(Callout).options(selectinload(Callout.members)).order_by(Callout.incident_date.desc())
    if date_from is not None:
        q = q.where(Callout.incident_date >= date_from)
    if date_to is not None:
        q = q.where(Callout.incident_date <= date_to)
    return list(db.scalars(q).all())


def get_callout(db: Session, callout_id: int) -> Callout | None:
    return db.scalars(select(Callout).where(Callout.id == callout_id).options(selectinload(Callout.members))).first()


def create_callout(db: Session, data: dict, member_ids: list[int], creator_id: int) -> Callout:
    members = list(db.scalars(select(Member).where(Member.id.in_(member_ids))).all()) if member_ids else []
    payload = {k: v for k, v in data.items() if k != "member_ids"}
    row = Callout(user_id=creator_id, **payload)
    row.members = members
    db.add(row)
    db.flush()
    return row


def update_callout(db: Session, callout: Callout, data: dict, member_ids: list[int] | None) -> Callout:
    for key, value in data.items():
        if hasattr(callout, key) and key != "member_ids":
            setattr(callout, key, value)
    if member_ids is not None:
        callout.members = list(db.scalars(select(Member).where(Member.id.in_(member_ids))).all())
    return callout


def delete_callout(db: Session, callout: Callout) -> None:
    db.delete(callout)
