from datetime import datetime, timedelta

from sqlalchemy import func, select
from sqlalchemy.orm import Session

from app.models.ampds_code import AMPDSCode
from app.models.callout import Callout
from app.models.defib import Defib
from app.models.member import Member


def member_stats(db: Session) -> dict[str, int]:
    now = datetime.utcnow()
    total = db.scalar(select(func.count()).select_from(Member).where(Member.deleted_at.is_(None))) or 0
    active = (
        db.scalar(
            select(func.count()).select_from(Member).where(Member.deleted_at.is_(None), Member.status == "active")
        )
        or 0
    )
    cert = (
        db.scalar(
            select(func.count())
            .select_from(Member)
            .where(Member.deleted_at.is_(None), Member.cfr_certificate_expiry < now.date() - timedelta(days=90))
        )
        or 0
    )
    vetting = (
        db.scalar(
            select(func.count())
            .select_from(Member)
            .where(
                Member.deleted_at.is_(None),
                Member.garda_vetting_date < (now - timedelta(days=3 * 365 - 90)).date(),
            )
        )
        or 0
    )
    return {"total": total, "active": active, "cert_expiring": cert, "vetting_expiring": vetting}


def defib_stats(db: Session) -> dict[str, int]:
    now = datetime.utcnow()
    total = db.scalar(select(func.count()).select_from(Defib).where(Defib.deleted_at.is_(None))) or 0
    public = (
        db.scalar(
            select(func.count()).select_from(Defib).where(Defib.deleted_at.is_(None), Defib.display_on_map.is_(True))
        )
        or 0
    )
    pads = (
        db.scalar(
            select(func.count())
            .select_from(Defib)
            .where(Defib.deleted_at.is_(None), Defib.pads_expire_at < now + timedelta(days=30))
        )
        or 0
    )
    battery = (
        db.scalar(
            select(func.count())
            .select_from(Defib)
            .where(
                Defib.deleted_at.is_(None),
                (Defib.battery_expires_at < now + timedelta(days=30)) | (Defib.battery_expires_at.is_(None)),
            )
        )
        or 0
    )
    return {"total": total, "public": public, "pads_expiring": pads, "battery_expiring": battery}


def callout_stats(db: Session, date_from: datetime | None = None, date_to: datetime | None = None) -> dict[str, int]:
    arrest_codes = list(db.scalars(select(AMPDSCode.code).where(AMPDSCode.arrest_code.is_(True))).all())
    q = select(Callout)
    if date_from:
        q = q.where(Callout.incident_date >= date_from)
    if date_to:
        q = q.where(Callout.incident_date <= date_to)
    calls = list(db.scalars(q).all())
    cardiac = [c for c in calls if c.ampds_code in arrest_codes]

    def count(items, pred):
        return sum(1 for i in items if pred(i))

    return {
        "total": len(calls),
        "mobilised": count(calls, lambda c: c.mobilised),
        "attended": count(calls, lambda c: c.attended),
        "missed": count(calls, lambda c: not c.mobilised and not c.attended and not c.medical_facility),
        "medical_facility": count(calls, lambda c: c.medical_facility),
        "cardiac": len(cardiac),
        "cardiac_mobilised": count(cardiac, lambda c: c.mobilised),
        "cardiac_attended": count(cardiac, lambda c: c.attended),
        "rosc": count(cardiac, lambda c: c.rosc_achieved),
    }
