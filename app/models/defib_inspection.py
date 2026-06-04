from __future__ import annotations

from datetime import datetime
from typing import TYPE_CHECKING

from sqlalchemy import BigInteger, DateTime, ForeignKey, String, Text
from sqlalchemy.orm import Mapped, mapped_column, relationship

from app.models.base import Base, PrimaryKeyMixin, SoftDeleteMixin, TimestampMixin

if TYPE_CHECKING:
    from app.models.defib import Defib
    from app.models.member import Member
    from app.models.user import User


class DefibInspection(Base, PrimaryKeyMixin, TimestampMixin, SoftDeleteMixin):
    __tablename__ = "defib_inspections"
    user_id: Mapped[int] = mapped_column(BigInteger, ForeignKey("users.id"))
    member_id: Mapped[int] = mapped_column(BigInteger, ForeignKey("members.id"))
    defib_id: Mapped[int] = mapped_column(BigInteger, ForeignKey("defibs.id", ondelete="CASCADE"))
    inspected_at: Mapped[datetime] = mapped_column(DateTime)
    pads_expire_at: Mapped[datetime] = mapped_column(DateTime)
    battery_expires_at: Mapped[datetime] = mapped_column(DateTime)
    battery_condition: Mapped[str | None] = mapped_column(String(255), nullable=True)
    notes: Mapped[str | None] = mapped_column(Text, nullable=True)

    member: Mapped[Member] = relationship()
    defib: Mapped[Defib] = relationship(back_populates="inspections")
    creator: Mapped[User] = relationship(foreign_keys=[user_id])
