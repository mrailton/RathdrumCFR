from __future__ import annotations

import logging
from zoneinfo import ZoneInfo

from apscheduler.schedulers.asyncio import AsyncIOScheduler
from apscheduler.triggers.cron import CronTrigger

from app.core.config import Settings, get_settings
from app.core.database import get_session_factory, mark_for_commit
from app.services import reports as report_service

logger = logging.getLogger(__name__)

_scheduler: AsyncIOScheduler | None = None

REPORT_JOBS: tuple[tuple[str, str], ...] = (
    ("run_cert_expiry_report", "CFR cert expiry"),
    ("run_battery_expiry_report", "Defib battery expiry"),
    ("run_defib_inspection_report", "Defib inspection"),
    ("run_defib_pad_expiry_report", "Defib pad expiry"),
    ("run_garda_vetting_report", "Garda vetting expiry"),
)


def _run_report(method_name: str, label: str) -> None:
    db = get_session_factory()()
    try:
        fn = getattr(report_service, method_name)
        fn(db)
        mark_for_commit(db)
        db.commit()
        logger.info("Scheduled report completed: %s", label)
    except Exception:
        db.rollback()
        logger.exception("Scheduled report failed: %s", label)
        raise
    finally:
        db.close()


def start_scheduler(settings: Settings | None = None) -> AsyncIOScheduler | None:
    global _scheduler
    settings = settings or get_settings()
    if not settings.scheduler_enabled:
        return None
    if _scheduler is not None:
        return _scheduler

    tz = ZoneInfo(settings.app_timezone)
    scheduler = AsyncIOScheduler(timezone=tz)
    trigger = CronTrigger(day=1, hour=9, minute=0, timezone=tz)

    for method_name, label in REPORT_JOBS:
        scheduler.add_job(
            _run_report,
            trigger=trigger,
            args=[method_name, label],
            id=f"report:{method_name}",
            replace_existing=True,
        )

    scheduler.start()
    _scheduler = scheduler
    logger.info("Report scheduler started (monthly on day 1 at 09:00 %s)", settings.app_timezone)
    return scheduler


def stop_scheduler() -> None:
    global _scheduler
    if _scheduler is not None:
        if _scheduler.running:
            _scheduler.shutdown(wait=False)
        _scheduler = None
