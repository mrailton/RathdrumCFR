from typing import Annotated

from fastapi import Form
from pydantic import BaseModel, EmailStr, Field


class CsrfForm(BaseModel):
    csrf_token: str = ""


class LoginForm(CsrfForm):
    email: EmailStr
    password: str
    remember_me: bool = False


def login_form(
    csrf_token: Annotated[str, Form()] = "",
    email: Annotated[str, Form()] = "",
    password: Annotated[str, Form()] = "",
    remember_me: Annotated[str | None, Form()] = None,
) -> LoginForm:
    return LoginForm(
        csrf_token=csrf_token,
        email=email,
        password=password,
        remember_me=remember_me == "on",
    )


class ContactForm(CsrfForm):
    name: str = Field(min_length=1, max_length=255)
    email: EmailStr
    phone: str = Field(min_length=1, max_length=255)
    message: str = Field(min_length=1)
    g_recaptcha_response: str = ""


def contact_form(
    csrf_token: Annotated[str, Form()] = "",
    name: Annotated[str, Form()] = "",
    email: Annotated[str, Form()] = "",
    phone: Annotated[str, Form()] = "",
    message: Annotated[str, Form()] = "",
    g_recaptcha_response: Annotated[str, Form(alias="g-recaptcha-response")] = "",
) -> ContactForm:
    return ContactForm(
        csrf_token=csrf_token,
        name=name,
        email=email,
        phone=phone,
        message=message,
        g_recaptcha_response=g_recaptcha_response,
    )
