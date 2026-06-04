from sqlalchemy import select
from sqlalchemy.orm import Session, selectinload

from app.models.member import Member
from app.models.training_session import TrainingSession


def list_training_sessions(db: Session) -> list[TrainingSession]:
    return list(
        db.scalars(
            select(TrainingSession).where(TrainingSession.deleted_at.is_(None)).order_by(TrainingSession.date.desc())
        ).all()
    )


def get_training_session(db: Session, session_id: int) -> TrainingSession | None:
    return db.scalars(
        select(TrainingSession).where(TrainingSession.id == session_id).options(selectinload(TrainingSession.members))
    ).first()


def save_training_session(
    db: Session, data: dict, member_ids: list[int], session_id: int | None = None
) -> TrainingSession:
    members = list(db.scalars(select(Member).where(Member.id.in_(member_ids))).all()) if member_ids else []
    if session_id:
        row = db.get(TrainingSession, session_id)
        if not row:
            raise ValueError("Training session not found")
        for k, v in data.items():
            setattr(row, k, v)
        row.members = members
        return row
    row = TrainingSession(**data)
    row.members = members
    db.add(row)
    db.flush()
    return row
