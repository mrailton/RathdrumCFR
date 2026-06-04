from app.services import members as member_service


def test_member_crud(db_session, admin_user):
    created = member_service.create_member(
        db_session,
        {"name": "Alice", "status": "active", "title": "Responder"},
        admin_user.id,
    )
    assert created.id is not None

    fetched = member_service.get_member(db_session, created.id)
    assert fetched is not None
    assert fetched.name == "Alice"

    member_service.update_member(db_session, created, {"name": "Alice Updated", "status": "inactive"})
    assert created.name == "Alice Updated"

    member_service.add_member_note(db_session, created.id, "Training note", admin_user.id)
    db_session.refresh(fetched)
    assert len(fetched.notes) == 1

    member_service.soft_delete_member(db_session, created)
    assert created.deleted_at is not None
    assert member_service.get_member(db_session, created.id) is not None
    assert created.id not in [m.id for m in member_service.list_members(db_session)]
