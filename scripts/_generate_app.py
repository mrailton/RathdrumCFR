#!/usr/bin/env python3
"""One-off generator for bulk app modules. Run: python3.14 scripts/_generate_app.py"""
from pathlib import Path

ROOT = Path(__file__).resolve().parent.parent


def w(rel: str, content: str) -> None:
    p = ROOT / rel
    p.parent.mkdir(parents=True, exist_ok=True)
    p.write_text(content.rstrip() + "\n")


# Services
w(
    "app/services/__init__.py",
    '"""Domain services."""',
)

w(
    "app/services/users.py",
    '''from sqlalchemy import select
from sqlalchemy.orm import Session

from app.core.security import hash_password, verify_password
from app.models.user import User


def get_user_by_id(db: Session, user_id: int) -> User | None:
    return db.get(User, user_id)


def get_user_by_email(db: Session, email: str) -> User | None:
    return db.scalars(select(User).where(User.email == email, User.deleted_at.is_(None))).first()


def authenticate(db: Session, email: str, password: str) -> User | None:
    user = get_user_by_email(db, email)
    if not user or not verify_password(password, user.password):
        return None
    return user


def list_users(db: Session) -> list[User]:
    return list(db.scalars(select(User).where(User.deleted_at.is_(None)).order_by(User.name)).all())


def create_user(db: Session, *, name: str, email: str, password: str, creator_id: int) -> User:
    user = User(name=name, email=email, password=hash_password(password))
    db.add(user)
    db.flush()
    return user


def update_user(db: Session, user: User, data: dict) -> User:
    for key, value in data.items():
        if key == "password" and value:
            user.password = hash_password(value)
        elif hasattr(user, key) and key != "password":
            setattr(user, key, value)
    return user
''',
)

w(
    "app/services/members.py",
    '''from datetime import date

from sqlalchemy import select
from sqlalchemy.orm import Session, selectinload

from app.models.member import Member
from app.models.member_note import MemberNote


def list_members(db: Session, *, include_trashed: bool = False) -> list[Member]:
    q = select(Member).order_by(Member.name)
    if not include_trashed:
        q = q.where(Member.deleted_at.is_(None))
    return list(db.scalars(q).all())


def get_member(db: Session, member_id: int) -> Member | None:
    return db.scalars(
        select(Member)
        .where(Member.id == member_id)
        .options(selectinload(Member.notes), selectinload(Member.callouts), selectinload(Member.training_sessions))
    ).first()


def create_member(db: Session, data: dict, creator_id: int) -> Member:
    member = Member(user_id=creator_id, **data)
    db.add(member)
    db.flush()
    return member


def update_member(db: Session, member: Member, data: dict) -> Member:
    for key, value in data.items():
        if hasattr(member, key):
            setattr(member, key, value)
    return member


def soft_delete_member(db: Session, member: Member) -> None:
    member.deleted_at = member.deleted_at or date.today()  # type: ignore[assignment]
    from datetime import datetime
    member.deleted_at = datetime.utcnow()


def add_member_note(db: Session, member_id: int, note: str, user_id: int) -> MemberNote:
    row = MemberNote(member_id=member_id, user_id=user_id, note=note)
    db.add(row)
    db.flush()
    return row
''',
)

w(
    "app/services/defibs.py",
    '''from datetime import datetime

from sqlalchemy import select
from sqlalchemy.orm import Session, selectinload

from app.models.defib import Defib
from app.models.defib_inspection import DefibInspection
from app.models.defib_note import DefibNote


def list_defibs(db: Session, *, include_trashed: bool = False) -> list[Defib]:
    q = select(Defib).order_by(Defib.name)
    if not include_trashed:
        q = q.where(Defib.deleted_at.is_(None))
    return list(db.scalars(q).all())


def list_public_defibs(db: Session) -> list[Defib]:
    return list(
        db.scalars(
            select(Defib).where(Defib.display_on_map.is_(True), Defib.deleted_at.is_(None), Defib.coordinates.isnot(None))
        ).all()
    )


def get_defib(db: Session, defib_id: int) -> Defib | None:
    return db.scalars(
        select(Defib)
        .where(Defib.id == defib_id)
        .options(selectinload(Defib.inspections), selectinload(Defib.notes))
    ).first()


def create_defib(db: Session, data: dict, creator_id: int) -> Defib:
    row = Defib(user_id=creator_id, **data)
    db.add(row)
    db.flush()
    return row


def update_defib(db: Session, defib: Defib, data: dict) -> Defib:
    for key, value in data.items():
        if hasattr(defib, key):
            setattr(defib, key, value)
    return defib


def soft_delete_defib(db: Session, defib: Defib) -> None:
    defib.deleted_at = datetime.utcnow()


def restore_defib(db: Session, defib: Defib) -> None:
    defib.deleted_at = None


def add_defib_note(db: Session, defib_id: int, note: str, user_id: int) -> DefibNote:
    row = DefibNote(defib_id=defib_id, user_id=user_id, note=note)
    db.add(row)
    db.flush()
    return row


def create_inspection(db: Session, defib: Defib, data: dict, creator_id: int) -> DefibInspection:
    inspection = DefibInspection(user_id=creator_id, defib_id=defib.id, **data)
    db.add(inspection)
    db.flush()
    member = inspection.member
    defib.pads_expire_at = inspection.pads_expire_at
    defib.battery_expires_at = inspection.battery_expires_at
    defib.last_inspected_at = inspection.inspected_at
    defib.last_inspected_by = member.name if member else None
    return inspection
''',
)

w(
    "app/services/callouts.py",
    '''from sqlalchemy import select
from sqlalchemy.orm import Session, selectinload

from app.models.callout import Callout
from app.models.member import Member


def list_callouts(db: Session, *, date_from=None, date_to=None) -> list[Callout]:
    q = select(Callout).options(selectinload(Callout.members)).order_by(Callout.incident_date.desc())
    if date_from is not None:
        q = q.where(Callout.incident_date >= date_from)
    if date_to is not None:
        q = q.where(Callout.incident_date <= date_to)
    return list(db.scalars(q).all())


def get_callout(db: Session, callout_id: int) -> Callout | None:
    return db.scalars(select(Callout).where(Callout.id == callout_id).options(selectinload(Callout.members))).first()


def create_callout(db: Session, data: dict, member_ids: list[int], creator_id: int) -> Callout:
    members = list(db.scalars(select(Member).where(Member.id.in_(member_ids))).all()) if member_ids else []
    payload = {k: v for k, v in data.items() if k != "member_ids"}
    row = Callout(user_id=creator_id, **payload)
    row.members = members
    db.add(row)
    db.flush()
    return row


def update_callout(db: Session, callout: Callout, data: dict, member_ids: list[int] | None) -> Callout:
    for key, value in data.items():
        if hasattr(callout, key) and key != "member_ids":
            setattr(callout, key, value)
    if member_ids is not None:
        callout.members = list(db.scalars(select(Member).where(Member.id.in_(member_ids))).all())
    return callout


def delete_callout(db: Session, callout: Callout) -> None:
    db.delete(callout)
''',
)

w(
    "app/services/training.py",
    '''from sqlalchemy import select
from sqlalchemy.orm import Session, selectinload

from app.models.member import Member
from app.models.training_session import TrainingSession


def list_training_sessions(db: Session) -> list[TrainingSession]:
    return list(db.scalars(select(TrainingSession).where(TrainingSession.deleted_at.is_(None)).order_by(TrainingSession.date.desc())).all())


def get_training_session(db: Session, session_id: int) -> TrainingSession | None:
    return db.scalars(
        select(TrainingSession).where(TrainingSession.id == session_id).options(selectinload(TrainingSession.members))
    ).first()


def save_training_session(db: Session, data: dict, member_ids: list[int], session_id: int | None = None) -> TrainingSession:
    members = list(db.scalars(select(Member).where(Member.id.in_(member_ids))).all()) if member_ids else []
    if session_id:
        row = db.get(TrainingSession, session_id)
        if not row:
            raise ValueError("Training session not found")
        for k, v in data.items():
            setattr(row, k, v)
        row.members = members
        return row
    row = TrainingSession(**data)
    row.members = members
    db.add(row)
    db.flush()
    return row
''',
)

w(
    "app/services/ampds.py",
    '''from sqlalchemy import select
from sqlalchemy.orm import Session

from app.models.ampds_code import AMPDSCode


def list_ampds_codes(db: Session) -> list[AMPDSCode]:
    return list(db.scalars(select(AMPDSCode).order_by(AMPDSCode.code)).all())


def save_ampds_code(db: Session, data: dict, code_id: int | None = None) -> AMPDSCode:
    if code_id:
        row = db.get(AMPDSCode, code_id)
        if not row:
            raise ValueError("AMPDS code not found")
        for k, v in data.items():
            setattr(row, k, v)
        return row
    row = AMPDSCode(**data)
    db.add(row)
    db.flush()
    return row


def delete_ampds_code(db: Session, code: AMPDSCode) -> None:
    db.delete(code)
''',
)

w(
    "app/services/dashboard.py",
    '''from datetime import datetime, timedelta

from sqlalchemy import func, select
from sqlalchemy.orm import Session

from app.models.ampds_code import AMPDSCode
from app.models.callout import Callout
from app.models.defib import Defib
from app.models.member import Member


def member_stats(db: Session) -> dict[str, int]:
    now = datetime.utcnow()
    total = db.scalar(select(func.count()).select_from(Member).where(Member.deleted_at.is_(None))) or 0
    active = db.scalar(select(func.count()).select_from(Member).where(Member.deleted_at.is_(None), Member.status == "active")) or 0
    cert = db.scalar(
        select(func.count()).select_from(Member).where(Member.deleted_at.is_(None), Member.cfr_certificate_expiry < now.date() - timedelta(days=90))
    ) or 0
    vetting = db.scalar(
        select(func.count()).select_from(Member).where(
            Member.deleted_at.is_(None),
            Member.garda_vetting_date < (now - timedelta(days=3 * 365 - 90)).date(),
        )
    ) or 0
    return {"total": total, "active": active, "cert_expiring": cert, "vetting_expiring": vetting}


def defib_stats(db: Session) -> dict[str, int]:
    now = datetime.utcnow()
    total = db.scalar(select(func.count()).select_from(Defib).where(Defib.deleted_at.is_(None))) or 0
    public = db.scalar(select(func.count()).select_from(Defib).where(Defib.deleted_at.is_(None), Defib.display_on_map.is_(True))) or 0
    pads = db.scalar(
        select(func.count()).select_from(Defib).where(Defib.deleted_at.is_(None), Defib.pads_expire_at < now + timedelta(days=30))
    ) or 0
    battery = db.scalar(
        select(func.count()).select_from(Defib).where(
            Defib.deleted_at.is_(None),
            (Defib.battery_expires_at < now + timedelta(days=30)) | (Defib.battery_expires_at.is_(None)),
        )
    ) or 0
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
''',
)

w(
    "app/services/reports.py",
    '''from datetime import datetime, timedelta

from sqlalchemy import or_, select
from sqlalchemy.orm import Session, joinedload

from app.models.defib import Defib
from app.models.member import Member
from app.models.user_report import UserReport
from app.services import mail as mail_service


def report_recipients(db: Session, flag: str) -> list[str]:
    rows = db.scalars(select(UserReport).options(joinedload(UserReport.user)).where(getattr(UserReport, flag).is_(True))).all()
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
    defibs = list(db.scalars(select(Defib).where(or_(Defib.last_inspected_at < cutoff, Defib.last_inspected_at.is_(None)))).all())
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
''',
)

w(
    "app/services/mail.py",
    '''import smtplib
from email.message import EmailMessage

from app.core.config import get_settings


def _send(to: str, subject: str, body: str) -> None:
    settings = get_settings()
    msg = EmailMessage()
    msg["Subject"] = subject
    msg["From"] = settings.mail_from
    msg["To"] = to
    msg.set_content(body)
    with smtplib.SMTP(settings.smtp_host, settings.smtp_port) as smtp:
        if settings.smtp_tls:
            smtp.starttls()
        if settings.smtp_user:
            smtp.login(settings.smtp_user, settings.smtp_password)
        smtp.send_message(msg)


def send_contact_email(name: str, email: str, phone: str, message: str) -> None:
    settings = get_settings()
    body = f"Name: {name}\\nEmail: {email}\\nPhone: {phone}\\n\\n{message}"
    _send(settings.admin_email, "Contact form submission", body)


def send_report_email(to: str, subject: str, items: list) -> None:
    lines = [str(item) for item in items]
    _send(to, subject, "\\n".join(lines) if lines else "No matching records.")


def send_defib_inspected_email(to: str, defib) -> None:
    _send(to, f"Defibrillator inspected: {defib.name or defib.location}", f"Location: {defib.location}")
''',
)

w(
    "app/services/recaptcha.py",
    '''import httpx

from app.core.config import get_settings


async def verify_recaptcha(token: str, action: str = "contact") -> bool:
    settings = get_settings()
    if not settings.recaptcha_secret_key:
        return settings.app_debug
    async with httpx.AsyncClient() as client:
        resp = await client.post(
            "https://www.google.com/recaptcha/api/siteverify",
            data={"secret": settings.recaptcha_secret_key, "response": token},
            timeout=10,
        )
        data = resp.json()
    return bool(data.get("success")) and float(data.get("score", 0)) >= settings.recaptcha_min_score
''',
)

print("Generated services")
