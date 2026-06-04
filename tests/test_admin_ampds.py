from sqlalchemy import select

from app.models.ampds_code import AMPDSCode
from tests.conftest import extract_csrf


def test_admin_ampds_create_and_delete(admin_client, db_session):
    page = admin_client.get("/admin/ampds")
    csrf = extract_csrf(page.text)

    response = admin_client.post(
        "/admin/ampds",
        data={
            "csrf_token": csrf,
            "code": "10D1",
            "description": "Chest pain",
            "arrest_code": "on",
            "far_code": "",
        },
        follow_redirects=False,
    )
    assert response.status_code == 303

    code = db_session.scalars(select(AMPDSCode).where(AMPDSCode.code == "10D1")).first()
    assert code is not None

    page = admin_client.get("/admin/ampds")
    csrf = extract_csrf(page.text)
    delete = admin_client.post(
        f"/admin/ampds/{code.id}/delete",
        data={"csrf_token": csrf},
        follow_redirects=False,
    )
    assert delete.status_code == 303
    assert db_session.get(AMPDSCode, code.id) is None
