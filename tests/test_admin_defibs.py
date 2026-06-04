from unittest.mock import patch

from sqlalchemy import select

from app.models.defib import Defib
from tests.conftest import extract_csrf


def test_operator_create_defib(operator_client, db_session):
    page = operator_client.get("/admin/defibs/create")
    csrf = extract_csrf(page.text)

    response = operator_client.post(
        "/admin/defibs/create",
        data={
            "csrf_token": csrf,
            "location": "GAA Club",
            "model": "Philips",
            "display_on_map": "on",
            "coordinates": "53.1,-6.2",
        },
        follow_redirects=False,
    )
    assert response.status_code == 303

    defib = db_session.scalars(select(Defib).where(Defib.location == "GAA Club")).first()
    assert defib is not None
    assert defib.display_on_map is True


def test_operator_edit_defib_and_add_note(operator_client, db_session, operator_user):
    from app.services import defibs as defib_service

    defib = defib_service.create_defib(db_session, {"location": "Hall", "model": "Zoll"}, operator_user.id)
    db_session.commit()

    edit_page = operator_client.get(f"/admin/defibs/{defib.id}/edit")
    csrf = extract_csrf(edit_page.text)
    operator_client.post(
        f"/admin/defibs/{defib.id}/edit",
        data={
            "csrf_token": csrf,
            "location": "Town Hall",
            "model": "Philips",
            "display_on_map": "on",
        },
        follow_redirects=False,
    )
    db_session.refresh(defib)
    assert defib.location == "Town Hall"

    show = operator_client.get(f"/admin/defibs/{defib.id}")
    csrf = extract_csrf(show.text)
    operator_client.post(
        f"/admin/defibs/{defib.id}/notes",
        data={"csrf_token": csrf, "note": "Pads checked"},
        follow_redirects=False,
    )
    db_session.refresh(defib)
    assert any(n.note == "Pads checked" for n in defib.notes)


@patch("app.routers.admin.defibs.report_service.notify_defib_inspected")
def test_add_inspection(mock_notify, operator_client, db_session, operator_user):
    from app.models.member import Member
    from app.services import defibs as defib_service

    member = Member(user_id=operator_user.id, name="Inspector", status="active")
    db_session.add(member)
    defib = defib_service.create_defib(db_session, {"location": "School", "model": "Zoll"}, operator_user.id)
    db_session.commit()

    show = operator_client.get(f"/admin/defibs/{defib.id}")
    csrf = extract_csrf(show.text)
    operator_client.post(
        f"/admin/defibs/{defib.id}/inspections",
        data={
            "csrf_token": csrf,
            "member_id": str(member.id),
            "inspected_at": "2026-01-15T10:00",
            "pads_expire_at": "2027-01-01T00:00",
            "battery_expires_at": "2027-06-01T00:00",
        },
        follow_redirects=False,
    )
    db_session.refresh(defib)
    assert defib.last_inspected_at is not None
    mock_notify.assert_called_once()


@patch("app.routers.admin.defibs.report_service.notify_defib_inspected")
def test_admin_soft_delete_and_restore(mock_notify, admin_client, db_session, admin_user):
    from app.services import defibs as defib_service

    defib = defib_service.create_defib(db_session, {"location": "School", "model": "Zoll"}, admin_user.id)
    db_session.commit()

    page = admin_client.get("/admin/defibs")
    csrf = extract_csrf(page.text)
    admin_client.post(f"/admin/defibs/{defib.id}/delete", data={"csrf_token": csrf}, follow_redirects=False)
    db_session.refresh(defib)
    assert defib.deleted_at is not None

    page = admin_client.get("/admin/defibs")
    csrf = extract_csrf(page.text)
    admin_client.post(f"/admin/defibs/{defib.id}/restore", data={"csrf_token": csrf}, follow_redirects=False)
    db_session.refresh(defib)
    assert defib.deleted_at is None
