import pytest

from app.models.ampds_code import AMPDSCode
from app.services import ampds as ampds_service


def test_ampds_crud(db_session):
    created = ampds_service.save_ampds_code(
        db_session,
        {
            "code": "09E1",
            "description": "Cardiac arrest",
            "arrest_code": True,
            "far_code": False,
        },
    )
    assert created.id is not None

    codes = ampds_service.list_ampds_codes(db_session)
    assert any(c.code == "09E1" for c in codes)

    ampds_service.save_ampds_code(
        db_session,
        {"code": "09E1", "description": "Updated", "arrest_code": True, "far_code": True},
        code_id=created.id,
    )
    assert db_session.get(AMPDSCode, created.id).description == "Updated"

    ampds_service.delete_ampds_code(db_session, created)
    db_session.flush()
    assert db_session.get(AMPDSCode, created.id) is None


def test_save_ampds_missing_raises(db_session):
    with pytest.raises(ValueError, match="not found"):
        ampds_service.save_ampds_code(
            db_session,
            {"code": "X", "description": "Y", "arrest_code": False, "far_code": False},
            code_id=99999,
        )
