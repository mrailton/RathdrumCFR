import pytest

from app.core.security import hash_password, verify_password


@pytest.mark.real_password_hash
def test_password_hash_roundtrip():
    hashed = hash_password("s3cret")
    assert hashed != "s3cret"
    assert verify_password("s3cret", hashed)
    assert not verify_password("wrong", hashed)
