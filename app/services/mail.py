import smtplib
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
    body = f"Name: {name}\nEmail: {email}\nPhone: {phone}\n\n{message}"
    _send(settings.admin_email, "Contact form submission", body)


def send_report_email(to: str, subject: str, items: list) -> None:
    lines = [str(item) for item in items]
    _send(to, subject, "\n".join(lines) if lines else "No matching records.")


def send_defib_inspected_email(to: str, defib) -> None:
    _send(to, f"Defibrillator inspected: {defib.name or defib.location}", f"Location: {defib.location}")
