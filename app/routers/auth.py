from datetime import datetime

from fastapi import APIRouter, Depends, Request
from fastapi.responses import RedirectResponse
from sqlalchemy.orm import Session

from app.core.database import mark_for_commit
from app.dependencies import CurrentUser, DbSession, require_guest, verify_csrf
from app.schemas.forms import LoginForm, login_form
from app import policies
from app.services.users import authenticate
from app.templating import flash, render

router = APIRouter(tags=["auth"])


@router.get("/admin/login", name="auth.login", dependencies=[Depends(require_guest)])
def login_form_page(request: Request):
    return render(request, "auth/login.html")


@router.post("/admin/login", name="auth.login.store", dependencies=[Depends(require_guest)])
def login_store(request: Request, db: DbSession, form: LoginForm = Depends(login_form)):
    verify_csrf(request, form.csrf_token)
    user = authenticate(db, str(form.email), form.password)
    if not user:
        return render(request, "auth/login.html", {"errors": {"email": "Invalid credentials."}}, status_code=422)
    if not policies.is_operator(db, user):
        return render(
            request,
            "auth/login.html",
            {"errors": {"email": "Your account has no assigned role (user or admin)."}},
            status_code=422,
        )
    request.session.clear()
    request.session["user_id"] = user.id
    if form.remember_me:
        request.session["remember_me"] = True
    user.last_login_at = datetime.utcnow()
    mark_for_commit(db)
    return RedirectResponse(url="/admin", status_code=303)


@router.post("/admin/logout", name="auth.logout")
def logout(request: Request, _user: CurrentUser):
    request.session.clear()
    return RedirectResponse(url="/admin/login", status_code=303)
