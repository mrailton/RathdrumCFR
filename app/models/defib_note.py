from __future__ import annotations

from typing import TYPE_CHECKING

from sqlalchemy import BigInteger, ForeignKey, Text
from sqlalchemy.orm import Mapped, mapped_column, relationship

from app.models.base import Base, PrimaryKeyMixin, SoftDeleteMixin, TimestampMixin

if TYPE_CHECKING:
    from app.models.defib import Defib
    from app.models.user import User


class DefibNote(Base, PrimaryKeyMixin, TimestampMixin, SoftDeleteMixin):
    __tablename__ = "defib_notes"
    defib_id: Mapped[int] = mapped_column(BigInteger, ForeignKey("defibs.id", ondelete="CASCADE"))
    user_id: Mapped[int] = mapped_column(BigInteger, ForeignKey("users.id"))
    note: Mapped[str] = mapped_column(Text)

    defib: Mapped[Defib] = relationship(back_populates="notes")
    author: Mapped[User] = relationship()
