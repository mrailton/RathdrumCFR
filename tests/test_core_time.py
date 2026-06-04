from app.core.time import utc_now


def test_utc_now_is_naive_utc():
    now = utc_now()
    assert now.tzinfo is None
    assert now.year >= 2026
