from __future__ import annotations

from datetime import date, datetime
from typing import TYPE_CHECKING

from sqlalchemy import BigInteger, Boolean, Date, DateTime, ForeignKey, String
from sqlalchemy.orm import Mapped, mapped_column, relationship

from app.models.base import Base, PrimaryKeyMixin, SoftDeleteMixin, TimestampMixin

if TYPE_CHECKING:
    from app.models.defib_inspection import DefibInspection
    from app.models.defib_note import DefibNote
    from app.models.user import User


class Defib(Base, PrimaryKeyMixin, TimestampMixin, SoftDeleteMixin):
    __tablename__ = "defibs"
    user_id: Mapped[int] = mapped_column(BigInteger, ForeignKey("users.id"))
    name: Mapped[str | None] = mapped_column(String(255), nullable=True)
    location: Mapped[str] = mapped_column(String(255))
    coordinates: Mapped[str | None] = mapped_column(String(255), nullable=True)
    display_on_map: Mapped[bool] = mapped_column(Boolean, default=False)
    model: Mapped[str] = mapped_column(String(255))
    serial: Mapped[str | None] = mapped_column(String(255), nullable=True)
    owner: Mapped[str] = mapped_column(String(255), default="RathdrumCFR")
    last_inspected_by: Mapped[str | None] = mapped_column(String(255), nullable=True)
    last_inspected_at: Mapped[datetime | None] = mapped_column(DateTime, nullable=True)
    last_serviced_at: Mapped[datetime | None] = mapped_column(DateTime, nullable=True)
    pads_expire_at: Mapped[datetime | None] = mapped_column(DateTime, nullable=True)
    battery_expires_at: Mapped[datetime | None] = mapped_column(DateTime, nullable=True)
    defib_lot_number: Mapped[str | None] = mapped_column(String(255), nullable=True)
    defib_manufacture_date: Mapped[date | None] = mapped_column(Date, nullable=True)
    battery_lot_number: Mapped[str | None] = mapped_column(String(255), nullable=True)
    battery_manufacture_date: Mapped[date | None] = mapped_column(Date, nullable=True)
    pads_lot_number: Mapped[str | None] = mapped_column(String(255), nullable=True)
    pads_manufacture_date: Mapped[date | None] = mapped_column(Date, nullable=True)

    creator: Mapped[User] = relationship(foreign_keys=[user_id])
    inspections: Mapped[list[DefibInspection]] = relationship(back_populates="defib")
    notes: Mapped[list[DefibNote]] = relationship(back_populates="defib")
