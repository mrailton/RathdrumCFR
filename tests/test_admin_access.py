def test_operator_can_access_dashboard(operator_client):
    response = operator_client.get("/admin/", follow_redirects=True)
    assert response.status_code == 200


def test_operator_can_access_callouts(operator_client):
    response = operator_client.get("/admin/callouts")
    assert response.status_code == 200


def test_operator_cannot_access_members(operator_client):
    response = operator_client.get("/admin/members", follow_redirects=False)
    assert response.status_code == 403


def test_operator_cannot_access_users(operator_client):
    response = operator_client.get("/admin/users", follow_redirects=False)
    assert response.status_code == 403


def test_admin_can_access_users(admin_client):
    response = admin_client.get("/admin/users")
    assert response.status_code == 200


def test_admin_can_access_ampds(admin_client):
    response = admin_client.get("/admin/ampds")
    assert response.status_code == 200


def test_admin_dashboard_renders(admin_client):
    response = admin_client.get("/admin", follow_redirects=True)
    assert response.status_code == 200
    assert "admin" in response.url.path
