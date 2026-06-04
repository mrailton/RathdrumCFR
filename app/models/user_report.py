from __future__ import annotations

from typing import TYPE_CHECKING

from sqlalchemy import BigInteger, Boolean, ForeignKey
from sqlalchemy.orm import Mapped, mapped_column, relationship

from app.models.base import Base, PrimaryKeyMixin, TimestampMixin

if TYPE_CHECKING:
    from app.models.user import User


class UserReport(Base, PrimaryKeyMixin, TimestampMixin):
    __tablename__ = "user_reports"
    user_id: Mapped[int] = mapped_column(BigInteger, ForeignKey("users.id"))
    cfr_cert_expiry: Mapped[bool] = mapped_column(Boolean, default=False)
    defib_battery_expiry: Mapped[bool] = mapped_column(Boolean, default=False)
    defib_inspection: Mapped[bool] = mapped_column(Boolean, default=False)
    defib_pad_expiry: Mapped[bool] = mapped_column(Boolean, default=False)
    garda_vetting_expiry: Mapped[bool] = mapped_column(Boolean, default=False)
    defib_inspected: Mapped[bool] = mapped_column(Boolean, default=False)

    user: Mapped[User] = relationship(back_populates="reports")
