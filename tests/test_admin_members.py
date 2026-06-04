from sqlalchemy import select

from app.models.member import Member
from tests.conftest import extract_csrf


def test_admin_members_index(admin_client):
    response = admin_client.get("/admin/members")
    assert response.status_code == 200


def test_admin_create_member(admin_client, db_session):
    page = admin_client.get("/admin/members/create")
    csrf = extract_csrf(page.text)

    response = admin_client.post(
        "/admin/members/create",
        data={
            "csrf_token": csrf,
            "name": "Bob Member",
            "status": "active",
            "title": "Responder",
        },
        follow_redirects=False,
    )
    assert response.status_code == 303

    member = db_session.scalars(select(Member).where(Member.name == "Bob Member")).first()
    assert member is not None


def test_admin_member_edit_and_delete(admin_client, db_session, admin_user):
    from app.services import members as member_service

    member = member_service.create_member(
        db_session,
        {"name": "Carol", "status": "active", "title": "Responder"},
        admin_user.id,
    )
    db_session.commit()

    edit_page = admin_client.get(f"/admin/members/{member.id}/edit")
    csrf = extract_csrf(edit_page.text)
    admin_client.post(
        f"/admin/members/{member.id}/edit",
        data={
            "csrf_token": csrf,
            "name": "Carol Updated",
            "status": "inactive",
            "title": "Responder",
        },
        follow_redirects=False,
    )
    db_session.refresh(member)
    assert member.name == "Carol Updated"

    page = admin_client.get("/admin/members")
    csrf = extract_csrf(page.text)
    admin_client.post(
        f"/admin/members/{member.id}/delete",
        data={"csrf_token": csrf},
        follow_redirects=False,
    )
    db_session.refresh(member)
    assert member.deleted_at is not None


def test_admin_member_show_and_note(admin_client, db_session, admin_user):
    from app.services import members as member_service

    member = member_service.create_member(
        db_session,
        {"name": "Dave", "status": "active", "title": "Responder"},
        admin_user.id,
    )
    db_session.commit()

    show = admin_client.get(f"/admin/members/{member.id}")
    assert show.status_code == 200

    csrf = extract_csrf(show.text)
    admin_client.post(
        f"/admin/members/{member.id}/notes",
        data={"csrf_token": csrf, "note": "Good session"},
        follow_redirects=False,
    )
    db_session.refresh(member)
    assert any(n.note == "Good session" for n in member.notes)
