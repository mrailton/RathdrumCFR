from datetime import UTC, datetime


def utc_now() -> datetime:
    """Naive UTC datetime for existing DB columns."""
    return datetime.now(UTC).replace(tzinfo=None)
