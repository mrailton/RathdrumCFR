from datetime import date

from sqlalchemy import select

from app.models.training_session import TrainingSession
from tests.conftest import extract_csrf


def test_admin_create_training(admin_client, db_session, admin_user):
    from app.models.member import Member

    member = Member(user_id=admin_user.id, name="Trainee", status="active")
    db_session.add(member)
    db_session.commit()

    page = admin_client.get("/admin/training/create")
    csrf = extract_csrf(page.text)
    response = admin_client.post(
        "/admin/training/create",
        data={
            "csrf_token": csrf,
            "date": "2026-05-01",
            "topic": "AED training",
            "member_ids": str(member.id),
        },
        follow_redirects=False,
    )
    assert response.status_code == 303

    session = db_session.scalars(select(TrainingSession).where(TrainingSession.topic == "AED training")).first()
    assert session is not None
    assert session.date == date(2026, 5, 1)


def test_admin_edit_training(admin_client, db_session, admin_user):
    from app.services import training as training_service

    created = training_service.save_training_session(
        db_session,
        {"date": date(2026, 6, 1), "topic": "Original", "notes": None},
        [],
    )
    db_session.commit()

    page = admin_client.get(f"/admin/training/{created.id}/edit")
    csrf = extract_csrf(page.text)
    admin_client.post(
        f"/admin/training/{created.id}/edit",
        data={"csrf_token": csrf, "date": "2026-06-02", "topic": "Updated topic"},
        follow_redirects=False,
    )
    db_session.refresh(created)
    assert created.topic == "Updated topic"
