import typer
from sqlalchemy.orm import Session

from app.core.config import get_settings
from app.core.database import get_session_factory, init_db, mark_for_commit
from app.core.security import hash_password
from app.models.user import User
from app.policies import ROLE_ADMIN, normalize_role
from app.services import reports as report_service

app = typer.Typer(help="Rathdrum CFR management commands")


def _session() -> Session:
    init_db()
    return get_session_factory()()


@app.command("user-create")
def user_create(
    name: str,
    email: str,
    password: str,
    role: str = typer.Option(ROLE_ADMIN, help="admin or user"),
) -> None:
    """Create a user with role admin or user."""
    db = _session()
    try:
        user = User(
            name=name,
            email=email,
            password=hash_password(password),
            role=normalize_role(role),
        )
        db.add(user)
        mark_for_commit(db)
        db.commit()
        typer.echo(f"Created {email} with role '{normalize_role(role)}'")
    finally:
        db.close()


@app.command("reports-cert-expiry")
def reports_cert_expiry() -> None:
    db = _session()
    try:
        report_service.run_cert_expiry_report(db)
        mark_for_commit(db)
        db.commit()
    finally:
        db.close()


@app.command("reports-battery-expiry")
def reports_battery_expiry() -> None:
    db = _session()
    try:
        report_service.run_battery_expiry_report(db)
        mark_for_commit(db)
        db.commit()
    finally:
        db.close()


@app.command("reports-defib-inspection")
def reports_defib_inspection() -> None:
    db = _session()
    try:
        report_service.run_defib_inspection_report(db)
        mark_for_commit(db)
        db.commit()
    finally:
        db.close()


@app.command("reports-defib-pad-expiry")
def reports_defib_pad_expiry() -> None:
    db = _session()
    try:
        report_service.run_defib_pad_expiry_report(db)
        mark_for_commit(db)
        db.commit()
    finally:
        db.close()


@app.command("reports-garda-vetting-expiry")
def reports_garda_vetting_expiry() -> None:
    db = _session()
    try:
        report_service.run_garda_vetting_report(db)
        mark_for_commit(db)
        db.commit()
    finally:
        db.close()


def main() -> None:
    get_settings()
    app()
