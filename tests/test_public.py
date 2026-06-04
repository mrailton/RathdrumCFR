from unittest.mock import AsyncMock, patch

from tests.conftest import extract_csrf


def test_contact_page(client):
    response = client.get("/contact")
    assert response.status_code == 200


def test_public_index_with_map_defib(client, db_session, admin_user):
    from app.services import defibs as defib_service

    defib_service.create_defib(
        db_session,
        {
            "location": "Park",
            "model": "Philips",
            "coordinates": "53.0,-6.0",
            "display_on_map": True,
        },
        admin_user.id,
    )
    db_session.flush()

    response = client.get("/")
    assert response.status_code == 200


@patch("app.routers.public.recaptcha_service.verify_recaptcha", new_callable=AsyncMock)
def test_contact_submit_recaptcha_failure(mock_recaptcha, client):
    mock_recaptcha.return_value = False

    page = client.get("/contact")
    csrf = extract_csrf(page.text)
    response = client.post(
        "/contact",
        data={
            "csrf_token": csrf,
            "name": "Jane",
            "email": "jane@test.com",
            "phone": "1",
            "message": "Hi",
            "g-recaptcha-response": "bad",
        },
        follow_redirects=False,
    )
    assert response.status_code == 303
    assert response.headers["location"] == "/contact"


@patch("app.routers.public.recaptcha_service.verify_recaptcha", new_callable=AsyncMock)
@patch("app.routers.public.mail_service.send_contact_email")
def test_contact_submit(mock_mail, mock_recaptcha, client):
    mock_recaptcha.return_value = True

    page = client.get("/contact")
    csrf = extract_csrf(page.text)
    response = client.post(
        "/contact",
        data={
            "csrf_token": csrf,
            "name": "Jane Doe",
            "email": "jane@example.com",
            "phone": "0871234567",
            "message": "Hello",
            "g-recaptcha-response": "token",
        },
        follow_redirects=False,
    )
    assert response.status_code == 303
    assert response.headers["location"] == "/"
    mock_mail.assert_called_once_with("Jane Doe", "jane@example.com", "0871234567", "Hello")
