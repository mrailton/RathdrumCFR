from app.routers.admin._helpers import form_str, form_str_optional, parse_bool


def test_form_str_helpers():
    assert form_str("hello") == "hello"
    assert form_str(None, default="x") == "x"
    assert form_str_optional("y") == "y"
    assert form_str_optional(None) is None


def test_parse_bool():
    assert parse_bool("1") is True
    assert parse_bool("on") is True
    assert parse_bool("yes") is True
    assert parse_bool("True") is True
    assert parse_bool("0") is False
    assert parse_bool(None) is False
    assert parse_bool("") is False
