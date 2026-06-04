from datetime import date
from unittest.mock import patch

from app.models.member import Member
from app.models.user_report import UserReport
from app.services import reports as report_service


def test_report_recipients(db_session, make_user):
    user = make_user(email="reports@test.com")
    report = UserReport(user_id=user.id, cfr_cert_expiry=True)
    db_session.add(report)
    db_session.flush()

    emails = report_service.report_recipients(db_session, "cfr_cert_expiry")
    assert "reports@test.com" in emails


@patch("app.services.reports.mail_service.send_report_email")
def test_run_cert_expiry_report(mock_send, db_session, make_user):
    user = make_user(email="admin-reports@test.com")
    db_session.add(UserReport(user_id=user.id, cfr_cert_expiry=True))
    db_session.add(
        Member(
            user_id=user.id,
            name="Expiring",
            status="active",
            cfr_certificate_expiry=date(2020, 1, 1),
        )
    )
    db_session.flush()

    report_service.run_cert_expiry_report(db_session)
    mock_send.assert_called_once()
    assert mock_send.call_args[0][0] == "admin-reports@test.com"


@patch("app.services.reports.mail_service.send_report_email")
def test_run_battery_expiry_report(mock_send, db_session, make_user):
    from app.models.defib import Defib

    user = make_user(email="battery@test.com")
    db_session.add(UserReport(user_id=user.id, defib_battery_expiry=True))
    db_session.add(Defib(user_id=user.id, location="X", model="M", battery_expires_at=None))
    db_session.flush()
    report_service.run_battery_expiry_report(db_session)
    mock_send.assert_called_once()


@patch("app.services.reports.mail_service.send_report_email")
def test_run_defib_pad_expiry_report(mock_send, db_session, make_user):
    from datetime import datetime

    from app.models.defib import Defib

    user = make_user(email="pads@test.com")
    db_session.add(UserReport(user_id=user.id, defib_pad_expiry=True))
    db_session.add(
        Defib(
            user_id=user.id,
            location="Y",
            model="M",
            pads_expire_at=datetime(2020, 1, 1),
        )
    )
    db_session.flush()
    report_service.run_defib_pad_expiry_report(db_session)
    mock_send.assert_called_once()


@patch("app.services.reports.mail_service.send_report_email")
def test_run_defib_inspection_report(mock_send, db_session, make_user):
    from app.models.defib import Defib

    user = make_user(email="inspect@test.com")
    db_session.add(UserReport(user_id=user.id, defib_inspection=True))
    db_session.add(Defib(user_id=user.id, location="Z", model="M", last_inspected_at=None))
    db_session.flush()
    report_service.run_defib_inspection_report(db_session)
    mock_send.assert_called_once()


@patch("app.services.reports.mail_service.send_report_email")
def test_run_garda_vetting_report(mock_send, db_session, make_user):
    user = make_user(email="vetting@test.com")
    db_session.add(UserReport(user_id=user.id, garda_vetting_expiry=True))
    db_session.add(
        Member(
            user_id=user.id,
            name="Vet",
            status="active",
            garda_vetting_date=date(2018, 1, 1),
        )
    )
    db_session.flush()
    report_service.run_garda_vetting_report(db_session)
    mock_send.assert_called_once()


@patch("app.services.reports.mail_service.send_defib_inspected_email")
def test_notify_defib_inspected(mock_send, db_session, make_user):
    from app.models.defib import Defib

    user = make_user(email="defib-notify@test.com")
    db_session.add(UserReport(user_id=user.id, defib_inspected=True))
    defib = Defib(user_id=user.id, location="Shop", model="Philips")
    db_session.add(defib)
    db_session.flush()

    report_service.notify_defib_inspected(db_session, defib)
    mock_send.assert_called_once_with("defib-notify@test.com", defib)
