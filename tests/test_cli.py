from unittest.mock import patch

from typer.testing import CliRunner

from app.cli import app

runner = CliRunner()


def test_user_create_cli(cli_db, db_session):
    result = runner.invoke(app, ["user-create", "CLI User", "cli@test.com", "secret123", "--role", "admin"])
    assert result.exit_code == 0, result.output
    assert "cli@test.com" in result.output

    from app.services.users import get_user_by_email

    assert get_user_by_email(db_session, "cli@test.com") is not None


@patch("app.cli.report_service.run_cert_expiry_report")
def test_reports_cert_expiry_cli(mock_report, cli_db):
    result = runner.invoke(app, ["reports-cert-expiry"])
    assert result.exit_code == 0
    mock_report.assert_called_once()


@patch("app.cli.report_service.run_battery_expiry_report")
def test_reports_battery_expiry_cli(mock_report, cli_db):
    result = runner.invoke(app, ["reports-battery-expiry"])
    assert result.exit_code == 0
    mock_report.assert_called_once()


@patch("app.cli.report_service.run_defib_inspection_report")
def test_reports_defib_inspection_cli(mock_report, cli_db):
    result = runner.invoke(app, ["reports-defib-inspection"])
    assert result.exit_code == 0
    mock_report.assert_called_once()


@patch("app.cli.report_service.run_defib_pad_expiry_report")
def test_reports_defib_pad_expiry_cli(mock_report, cli_db):
    result = runner.invoke(app, ["reports-defib-pad-expiry"])
    assert result.exit_code == 0
    mock_report.assert_called_once()


@patch("app.cli.report_service.run_garda_vetting_report")
def test_reports_garda_vetting_cli(mock_report, cli_db):
    result = runner.invoke(app, ["reports-garda-vetting-expiry"])
    assert result.exit_code == 0
    mock_report.assert_called_once()
