from datetime import date

import pytest

from app.services import training as training_service


def test_training_session_save(db_session, admin_user):
    from app.models.member import Member

    member = Member(user_id=admin_user.id, name="Trainee", status="active")
    db_session.add(member)
    db_session.flush()

    created = training_service.save_training_session(
        db_session,
        {"date": date(2026, 3, 1), "topic": "CPR refresh", "notes": None},
        [member.id],
    )
    assert created.id is not None
    assert len(created.members) == 1

    training_service.save_training_session(
        db_session,
        {"date": date(2026, 3, 2), "topic": "AED", "notes": "Done"},
        [],
        session_id=created.id,
    )
    assert created.topic == "AED"

    sessions = training_service.list_training_sessions(db_session)
    assert any(s.id == created.id for s in sessions)


def test_training_session_not_found(db_session):
    with pytest.raises(ValueError, match="not found"):
        training_service.save_training_session(
            db_session,
            {"date": date(2026, 1, 1), "topic": "X", "notes": None},
            [],
            session_id=99999,
        )
