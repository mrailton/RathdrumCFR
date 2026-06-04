from __future__ import annotations

from datetime import date
from typing import TYPE_CHECKING

from sqlalchemy import BigInteger, Column, Date, ForeignKey, String, Table, Text
from sqlalchemy.orm import Mapped, mapped_column, relationship

from app.models.base import Base, PrimaryKeyMixin, SoftDeleteMixin, TimestampMixin

if TYPE_CHECKING:
    from app.models.member import Member

member_training_session = Table(
    "member_training_session",
    Base.metadata,
    Column("training_session_id", BigInteger, ForeignKey("training_sessions.id", ondelete="CASCADE"), primary_key=True),
    Column("member_id", BigInteger, ForeignKey("members.id", ondelete="CASCADE"), primary_key=True),
)


class TrainingSession(Base, PrimaryKeyMixin, TimestampMixin, SoftDeleteMixin):
    __tablename__ = "training_sessions"
    date: Mapped[date] = mapped_column(Date)
    topic: Mapped[str] = mapped_column(String(255))
    notes: Mapped[str | None] = mapped_column(Text, nullable=True)

    members: Mapped[list[Member]] = relationship(
        secondary=member_training_session, back_populates="training_sessions"
    )
