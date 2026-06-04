from __future__ import annotations

from datetime import date
from typing import TYPE_CHECKING

from sqlalchemy import BigInteger, Date, ForeignKey, String
from sqlalchemy.orm import Mapped, mapped_column, relationship

from app.models.base import Base, PrimaryKeyMixin, SoftDeleteMixin, TimestampMixin

if TYPE_CHECKING:
    from app.models.callout import Callout
    from app.models.member_note import MemberNote
    from app.models.training_session import TrainingSession
    from app.models.user import User


class Member(Base, PrimaryKeyMixin, TimestampMixin, SoftDeleteMixin):
    __tablename__ = "members"
    user_id: Mapped[int] = mapped_column(BigInteger, ForeignKey("users.id"))
    name: Mapped[str] = mapped_column(String(255))
    phone: Mapped[str | None] = mapped_column(String(255), nullable=True)
    email: Mapped[str | None] = mapped_column(String(255), nullable=True)
    address_1: Mapped[str | None] = mapped_column(String(255), nullable=True)
    address_2: Mapped[str | None] = mapped_column(String(255), nullable=True)
    eircode: Mapped[str | None] = mapped_column(String(255), nullable=True)
    title: Mapped[str] = mapped_column(String(255), default="Responder")
    status: Mapped[str] = mapped_column(String(255), default="Inactive")
    cfr_certificate_number: Mapped[str | None] = mapped_column(String(255), nullable=True)
    cfr_certificate_expiry: Mapped[date | None] = mapped_column(Date, nullable=True)
    volunteer_declaration: Mapped[date | None] = mapped_column(Date, nullable=True)
    garda_vetting_id: Mapped[str | None] = mapped_column(String(255), nullable=True)
    garda_vetting_date: Mapped[date | None] = mapped_column(Date, nullable=True)
    cism_completed: Mapped[date | None] = mapped_column(Date, nullable=True)
    child_first_completed: Mapped[date | None] = mapped_column(Date, nullable=True)
    ppe_community_completed: Mapped[date | None] = mapped_column(Date, nullable=True)
    ppe_acute_completed: Mapped[date | None] = mapped_column(Date, nullable=True)
    hand_hygiene_completed: Mapped[date | None] = mapped_column(Date, nullable=True)
    hiqa_completed: Mapped[date | None] = mapped_column(Date, nullable=True)
    covid_return_completed: Mapped[date | None] = mapped_column(Date, nullable=True)
    ppe_assessment_completed: Mapped[date | None] = mapped_column(Date, nullable=True)

    creator: Mapped[User] = relationship(foreign_keys=[user_id])
    notes: Mapped[list[MemberNote]] = relationship(back_populates="member")
    callouts: Mapped[list[Callout]] = relationship(secondary="callout_member", back_populates="members")
    training_sessions: Mapped[list[TrainingSession]] = relationship(
        secondary="member_training_session", back_populates="members"
    )
