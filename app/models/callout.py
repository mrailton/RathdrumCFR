from __future__ import annotations

from datetime import datetime
from typing import TYPE_CHECKING

from sqlalchemy import BigInteger, Boolean, Column, DateTime, ForeignKey, Integer, String, Table, Text
from sqlalchemy.orm import Mapped, mapped_column, relationship

from app.models.base import Base, PrimaryKeyMixin, TimestampMixin

if TYPE_CHECKING:
    from app.models.member import Member
    from app.models.user import User

callout_member = Table(
    "callout_member",
    Base.metadata,
    Column("callout_id", BigInteger, ForeignKey("callouts.id", ondelete="CASCADE"), primary_key=True),
    Column("member_id", BigInteger, ForeignKey("members.id", ondelete="CASCADE"), primary_key=True),
)


class Callout(Base, PrimaryKeyMixin, TimestampMixin):
    __tablename__ = "callouts"
    user_id: Mapped[int] = mapped_column(BigInteger, ForeignKey("users.id"))
    incident_number: Mapped[str] = mapped_column(String(255))
    incident_date: Mapped[datetime] = mapped_column(DateTime)
    ampds_code: Mapped[str] = mapped_column(String(255))
    age: Mapped[str | None] = mapped_column(String(255), nullable=True)
    gender: Mapped[str] = mapped_column(String(255), default="Unknown")
    mobilised: Mapped[bool] = mapped_column(Boolean, default=False)
    medical_facility: Mapped[bool] = mapped_column(Boolean, default=False)
    attended: Mapped[bool] = mapped_column(Boolean, default=False)
    ohca_at_scene: Mapped[bool] = mapped_column(Boolean, default=False)
    bystander_cpr: Mapped[bool] = mapped_column(Boolean, default=False)
    rosc_achieved: Mapped[bool] = mapped_column(Boolean, default=False)
    patient_transported: Mapped[bool] = mapped_column(Boolean, default=False)
    source_of_aed: Mapped[str | None] = mapped_column(String(255), nullable=True)
    number_of_shocks_given: Mapped[int] = mapped_column(Integer, default=0)
    responders_at_scene: Mapped[int | None] = mapped_column(Integer, nullable=True, default=0)
    ppe_kits_used: Mapped[str | None] = mapped_column(String(255), nullable=True)
    waste_disposal: Mapped[str | None] = mapped_column(String(255), nullable=True)
    notes: Mapped[str | None] = mapped_column(Text, nullable=True)

    creator: Mapped[User] = relationship(foreign_keys=[user_id])
    members: Mapped[list[Member]] = relationship(secondary=callout_member, back_populates="callouts")
