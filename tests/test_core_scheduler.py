from unittest.mock import patch

from app.core.config import Settings
from app.core.scheduler import REPORT_JOBS, _run_report, start_scheduler, stop_scheduler


def test_start_scheduler_disabled():
    settings = Settings(app_env="testing", scheduler_enabled=False)
    assert start_scheduler(settings) is None


@patch("app.core.scheduler.AsyncIOScheduler.start")
def test_start_and_stop_scheduler(mock_start, cli_db):
    import app.core.scheduler as sched

    sched._scheduler = None
    settings = Settings(app_env="local", scheduler_enabled=True, app_timezone="UTC")
    scheduler = start_scheduler(settings)
    mock_start.assert_called_once()
    assert scheduler is not None
    assert len(scheduler.get_jobs()) == len(REPORT_JOBS)
    stop_scheduler()
    assert sched._scheduler is None


@patch("app.core.scheduler.report_service.run_cert_expiry_report")
def test_run_report_job(mock_fn, cli_db, db_session):
    _run_report("run_cert_expiry_report", "CFR cert expiry")
    mock_fn.assert_called_once_with(db_session)
