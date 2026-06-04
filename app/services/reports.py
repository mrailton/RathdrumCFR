from datetime import datetime, timedelta

from sqlalchemy import or_, select
from sqlalchemy.orm import Session, joinedload

from app.models.defib import Defib
from app.models.member import Member
from app.models.user_report import UserReport
from app.services import mail as mail_service


def report_recipients(db: Session, flag: str) -> list[str]:
    rows = db.scalars(
        select(UserReport).options(joinedload(UserReport.user)).where(getattr(UserReport, flag).is_(True))
    ).all()
    return [r.user.email for r in rows if r.user and r.user.email]


def run_cert_expiry_report(db: Session) -> None:
    cutoff = datetime.utcnow() + timedelta(days=60)
    members = list(db.scalars(select(Member).where(Member.cfr_certificate_expiry < cutoff.date())).all())
    for email in report_recipients(db, "cfr_cert_expiry"):
        mail_service.send_report_email(email, "CFR Certificate Expiry Report", members)


def run_battery_expiry_report(db: Session) -> None:
    cutoff = datetime.utcnow() + timedelta(days=30)
    defibs = list(
        db.scalars(
            select(Defib).where(or_(Defib.battery_expires_at < cutoff, Defib.battery_expires_at.is_(None)))
        ).all()
    )
    for email in report_recipients(db, "defib_battery_expiry"):
        mail_service.send_report_email(email, "Defibrillator Battery Expiry Report", defibs)


def run_defib_pad_expiry_report(db: Session) -> None:
    cutoff = datetime.utcnow() + timedelta(days=30)
    defibs = list(db.scalars(select(Defib).where(Defib.pads_expire_at < cutoff)).all())
    for email in report_recipients(db, "defib_pad_expiry"):
        mail_service.send_report_email(email, "Defibrillator Pad Expiry Report", defibs)


def run_defib_inspection_report(db: Session) -> None:
    cutoff = datetime.utcnow() - timedelta(days=30)
    defibs = list(
        db.scalars(select(Defib).where(or_(Defib.last_inspected_at < cutoff, Defib.last_inspected_at.is_(None)))).all()
    )
    for email in report_recipients(db, "defib_inspection"):
        mail_service.send_report_email(email, "Defibrillator Inspection Report", defibs)


def run_garda_vetting_report(db: Session) -> None:
    cutoff = datetime.utcnow() - timedelta(days=3 * 365 - 60)
    members = list(db.scalars(select(Member).where(Member.garda_vetting_date < cutoff.date())).all())
    for email in report_recipients(db, "garda_vetting_expiry"):
        mail_service.send_report_email(email, "Garda Vetting Expiry Report", members)


def notify_defib_inspected(db: Session, defib: Defib) -> None:
    for email in report_recipients(db, "defib_inspected"):
        mail_service.send_defib_inspected_email(email, defib)
