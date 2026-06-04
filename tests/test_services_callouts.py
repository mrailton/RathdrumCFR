from datetime import datetime

from app.services import callouts as callout_service


def test_list_callouts_date_filter(db_session, admin_user):
    from datetime import datetime

    from app.services import callouts as callout_service

    callout_service.create_callout(
        db_session,
        {
            "incident_number": "OLD",
            "incident_date": datetime(2025, 1, 1, 12, 0),
            "ampds_code": "09E1",
            "gender": "Unknown",
            "mobilised": False,
            "medical_facility": False,
            "attended": False,
            "ohca_at_scene": False,
            "bystander_cpr": False,
            "rosc_achieved": False,
            "patient_transported": False,
            "number_of_shocks_given": 0,
        },
        [],
        admin_user.id,
    )
    callout_service.create_callout(
        db_session,
        {
            "incident_number": "NEW",
            "incident_date": datetime(2026, 6, 1, 12, 0),
            "ampds_code": "09E1",
            "gender": "Unknown",
            "mobilised": False,
            "medical_facility": False,
            "attended": False,
            "ohca_at_scene": False,
            "bystander_cpr": False,
            "rosc_achieved": False,
            "patient_transported": False,
            "number_of_shocks_given": 0,
        },
        [],
        admin_user.id,
    )
    filtered = callout_service.list_callouts(
        db_session,
        date_from=datetime(2026, 1, 1),
        date_to=datetime(2026, 12, 31),
    )
    assert len(filtered) == 1
    assert filtered[0].incident_number == "NEW"


def test_callout_crud(db_session, admin_user):
    from app.models.member import Member

    member = Member(user_id=admin_user.id, name="Responder", status="active")
    db_session.add(member)
    db_session.flush()

    created = callout_service.create_callout(
        db_session,
        {
            "incident_number": "2026-001",
            "incident_date": datetime(2026, 2, 1, 14, 30),
            "ampds_code": "09E1",
            "gender": "Male",
            "mobilised": True,
            "medical_facility": False,
            "attended": True,
            "ohca_at_scene": False,
            "bystander_cpr": False,
            "rosc_achieved": False,
            "patient_transported": False,
            "number_of_shocks_given": 0,
        },
        [member.id],
        admin_user.id,
    )
    assert len(created.members) == 1

    listed = callout_service.list_callouts(db_session)
    assert any(c.id == created.id for c in listed)

    fetched = callout_service.get_callout(db_session, created.id)
    assert fetched is not None

    callout_service.update_callout(
        db_session,
        created,
        {"incident_number": "2026-001A", "notes": "Updated"},
        None,
    )
    assert created.incident_number == "2026-001A"

    callout_service.delete_callout(db_session, created)
    assert callout_service.get_callout(db_session, created.id) is None
