from sqlalchemy import select
from sqlalchemy.orm import Session, selectinload

from app.models.member import Member
from app.models.member_note import MemberNote


def list_members(db: Session, *, include_trashed: bool = False) -> list[Member]:
    q = select(Member).order_by(Member.name)
    if not include_trashed:
        q = q.where(Member.deleted_at.is_(None))
    return list(db.scalars(q).all())


def get_member(db: Session, member_id: int) -> Member | None:
    return db.scalars(
        select(Member)
        .where(Member.id == member_id)
        .options(selectinload(Member.notes), selectinload(Member.callouts), selectinload(Member.training_sessions))
    ).first()


def create_member(db: Session, data: dict, creator_id: int) -> Member:
    member = Member(user_id=creator_id, **data)
    db.add(member)
    db.flush()
    return member


def update_member(db: Session, member: Member, data: dict) -> Member:
    for key, value in data.items():
        if hasattr(member, key):
            setattr(member, key, value)
    return member


def soft_delete_member(db: Session, member: Member) -> None:
    from app.core.time import utc_now

    member.deleted_at = utc_now()


def add_member_note(db: Session, member_id: int, note: str, user_id: int) -> MemberNote:
    row = MemberNote(member_id=member_id, user_id=user_id, note=note)
    db.add(row)
    db.flush()
    return row
