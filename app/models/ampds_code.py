from sqlalchemy import Boolean, String
from sqlalchemy.orm import Mapped, mapped_column

from app.models.base import Base, PrimaryKeyMixin, TimestampMixin


class AMPDSCode(Base, PrimaryKeyMixin, TimestampMixin):
    __tablename__ = "ampds_codes"
    code: Mapped[str] = mapped_column(String(255))
    description: Mapped[str] = mapped_column(String(255))
    arrest_code: Mapped[bool] = mapped_column(Boolean, default=False)
    far_code: Mapped[bool] = mapped_column(Boolean, default=False)
