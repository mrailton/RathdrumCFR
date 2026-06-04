from unittest.mock import MagicMock, patch

from app.services import mail as mail_service


@patch("app.services.mail.smtplib.SMTP")
def test_send_report_and_defib_emails(mock_smtp):
    mock_server = MagicMock()
    mock_smtp.return_value.__enter__.return_value = mock_server

    mail_service.send_report_email("a@test.com", "Subject", ["item1", "item2"])

    class Defib:
        name = "Unit"
        location = "Here"

    mail_service.send_defib_inspected_email("b@test.com", Defib())
    assert mock_server.send_message.call_count == 2


@patch("app.services.mail.smtplib.SMTP")
def test_send_with_tls_and_login(mock_smtp, monkeypatch):
    monkeypatch.setenv("SMTP_TLS", "true")
    monkeypatch.setenv("SMTP_USER", "user")
    monkeypatch.setenv("SMTP_PASSWORD", "pass")
    from app.core.config import get_settings

    get_settings.cache_clear()
    mock_server = MagicMock()
    mock_smtp.return_value.__enter__.return_value = mock_server

    try:
        mail_service.send_contact_email("Jane", "jane@test.com", "1", "Hi")
        mock_server.starttls.assert_called_once()
        mock_server.login.assert_called_once_with("user", "pass")
    finally:
        get_settings.cache_clear()


@patch("app.services.mail.smtplib.SMTP")
def test_send_contact_email(mock_smtp):
    mock_server = MagicMock()
    mock_smtp.return_value.__enter__.return_value = mock_server

    mail_service.send_contact_email("Jane", "jane@test.com", "087", "Hello")

    mock_smtp.assert_called_once()
    mock_server.send_message.assert_called_once()
    msg = mock_server.send_message.call_args[0][0]
    assert "Jane" in msg.get_content()
