from datetime import datetime

from sqlalchemy import select

from app.models.callout import Callout
from tests.conftest import extract_csrf


def _callout_form_data(csrf: str, incident_number: str = "INC-EDIT") -> dict:
    return {
        "csrf_token": csrf,
        "incident_number": incident_number,
        "incident_date": "2026-04-01T10:00",
        "ampds_code": "09E1",
        "gender": "Female",
        "mobilised": "1",
        "medical_facility": "0",
        "attended": "1",
        "ohca_at_scene": "0",
        "bystander_cpr": "0",
        "rosc_achieved": "0",
        "patient_transported": "0",
        "number_of_shocks_given": "0",
        "responders_at_scene": "1",
    }


def test_operator_create_callout(operator_client, db_session, operator_user):
    page = operator_client.get("/admin/callouts/create")
    csrf = extract_csrf(page.text)

    response = operator_client.post(
        "/admin/callouts/create",
        data=_callout_form_data(csrf, "INC-99"),
        follow_redirects=False,
    )
    assert response.status_code == 303

    callout = db_session.scalars(select(Callout).where(Callout.incident_number == "INC-99")).first()
    assert callout is not None
    assert callout.user_id == operator_user.id


def test_callout_show_and_edit(operator_client, db_session, operator_user):
    from app.services import callouts as callout_service

    callout = callout_service.create_callout(
        db_session,
        {
            "incident_number": "INC-100",
            "incident_date": datetime(2026, 4, 2, 9, 0),
            "ampds_code": "10D1",
            "gender": "Male",
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
        operator_user.id,
    )
    db_session.commit()

    show = operator_client.get(f"/admin/callouts/{callout.id}")
    assert show.status_code == 200

    edit_page = operator_client.get(f"/admin/callouts/{callout.id}/edit")
    csrf = extract_csrf(edit_page.text)
    operator_client.post(
        f"/admin/callouts/{callout.id}/edit",
        data=_callout_form_data(csrf, "INC-100-UPDATED"),
        follow_redirects=False,
    )
    db_session.refresh(callout)
    assert callout.incident_number == "INC-100-UPDATED"


def test_callout_show_missing_redirects(operator_client):
    response = operator_client.get("/admin/callouts/99999", follow_redirects=False)
    assert response.status_code == 303


def test_admin_deletes_callout(admin_client, db_session, operator_user):
    from app.services import callouts as callout_service

    callout = callout_service.create_callout(
        db_session,
        {
            "incident_number": "INC-DEL",
            "incident_date": datetime(2026, 1, 1, 12, 0),
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
        operator_user.id,
    )
    db_session.commit()

    page = admin_client.get("/admin/callouts")
    csrf = extract_csrf(page.text)
    admin_client.post(
        f"/admin/callouts/{callout.id}/delete",
        data={"csrf_token": csrf},
        follow_redirects=False,
    )
    assert callout_service.get_callout(db_session, callout.id) is None
