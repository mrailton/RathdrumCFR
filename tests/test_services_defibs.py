from datetime import datetime

from app.services import defibs as defib_service


def test_defib_crud_and_public_list(db_session, admin_user):
    created = defib_service.create_defib(
        db_session,
        {
            "location": "Library",
            "model": "Philips",
            "coordinates": "53.0,-6.0",
            "display_on_map": True,
        },
        admin_user.id,
    )
    assert created.id is not None

    public = defib_service.list_public_defibs(db_session)
    assert any(d.id == created.id for d in public)

    fetched = defib_service.get_defib(db_session, created.id)
    assert fetched is not None

    defib_service.update_defib(db_session, created, {"name": "Town Defib"})
    assert created.name == "Town Defib"

    defib_service.add_defib_note(db_session, created.id, "Checked pads", admin_user.id)
    db_session.refresh(fetched)
    assert len(fetched.notes) == 1

    defib_service.soft_delete_defib(db_session, created)
    assert created.deleted_at is not None
    defib_service.restore_defib(db_session, created)
    assert created.deleted_at is None


def test_create_inspection_updates_defib(db_session, admin_user):
    from app.models.member import Member

    member = Member(user_id=admin_user.id, name="Inspector", status="active")
    db_session.add(member)
    db_session.flush()

    defib = defib_service.create_defib(
        db_session,
        {"location": "Hall", "model": "Zoll"},
        admin_user.id,
    )
    inspected_at = datetime(2026, 1, 15, 10, 0)
    pads_expire = datetime(2027, 1, 1, 0, 0)
    battery_expire = datetime(2027, 6, 1, 0, 0)

    defib_service.create_inspection(
        db_session,
        defib,
        {
            "member_id": member.id,
            "inspected_at": inspected_at,
            "pads_expire_at": pads_expire,
            "battery_expires_at": battery_expire,
            "battery_condition": "Good",
            "notes": "OK",
        },
        admin_user.id,
    )
    assert defib.last_inspected_at == inspected_at
    assert defib.pads_expire_at == pads_expire
    assert defib.last_inspected_by == "Inspector"
