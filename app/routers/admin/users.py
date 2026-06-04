from fastapi import APIRouter, Form, Request
from fastapi.responses import RedirectResponse

from app.core.database import mark_for_commit
from app.dependencies import CurrentUser, DbSession, verify_csrf
from app.policies import ROLE_ADMIN, ROLE_USER
from app.routers.admin._helpers import parse_bool, require_admin
from app.services import users as user_service
from app.templating import flash, render

router = APIRouter(prefix="/users", tags=["admin.users"])

REPORT_FLAGS = (
    ("cfr_cert_expiry", "CFR certificate expiry"),
    ("defib_battery_expiry", "Defibrillator battery expiry"),
    ("defib_inspection", "Defibrillator inspection overdue"),
    ("defib_pad_expiry", "Defibrillator pad expiry"),
    ("garda_vetting_expiry", "Garda vetting expiry"),
    ("defib_inspected", "Defibrillator inspected notifications"),
)


@router.get("", name="admin.users.index")
def index(request: Request, db: DbSession, user: CurrentUser):
    require_admin(db, user)
    return render(request, "admin/users/index.html", {"users": user_service.list_users(db)}, user=user, db=db)


@router.get("/create", name="admin.users.create")
def create_form(request: Request, db: DbSession, user: CurrentUser):
    require_admin(db, user)
    return _form(request, db, user, None)


@router.post("/create", name="admin.users.store")
async def store(request: Request, db: DbSession, user: CurrentUser, csrf_token: str = Form("")):
    require_admin(db, user)
    verify_csrf(request, csrf_token)
    form = await request.form()
    new_user = user_service.create_user(
        db,
        name=str(form.get("name", "")),
        email=str(form.get("email", "")),
        password=str(form.get("password", "")),
        creator_id=user.id,
        role=_role_name(form),
    )
    user_service.update_user_report_flags(db, new_user, _report_flags(form))
    mark_for_commit(db)
    flash(request, "success", "User created.")
    return RedirectResponse("/admin/users", status_code=303)


@router.get("/{user_id}/edit", name="admin.users.edit")
def edit_form(user_id: int, request: Request, db: DbSession, user: CurrentUser):
    require_admin(db, user)
    edit_user = user_service.get_user_by_id(db, user_id)
    if not edit_user:
        return RedirectResponse("/admin/users", status_code=303)
    return _form(request, db, user, edit_user)


@router.post("/{user_id}/edit", name="admin.users.update")
async def update(user_id: int, request: Request, db: DbSession, user: CurrentUser, csrf_token: str = Form("")):
    require_admin(db, user)
    verify_csrf(request, csrf_token)
    edit_user = user_service.get_user_by_id(db, user_id)
    if not edit_user:
        return RedirectResponse("/admin/users", status_code=303)
    form = await request.form()
    data = {"name": str(form.get("name", "")), "email": str(form.get("email", "")), "role": _role_name(form)}
    password = form.get("password")
    if password:
        data["password"] = str(password)
    user_service.update_user(db, edit_user, data)
    user_service.update_user_report_flags(db, edit_user, _report_flags(form))
    mark_for_commit(db)
    flash(request, "success", "User updated.")
    return RedirectResponse(f"/admin/users/{user_id}/edit", status_code=303)


def _form(request, db, current_user, edit_user):
    report_flags: dict[str, bool] = {}
    current_role = ROLE_USER
    if edit_user:
        current_role = user_service.get_user_role_name(edit_user)
        if edit_user.reports:
            for key, _ in REPORT_FLAGS:
                report_flags[key] = bool(getattr(edit_user.reports, key))
    return render(
        request,
        "admin/users/form.html",
        {
            "edit_user": edit_user,
            "current_role": current_role,
            "report_flags": report_flags,
            "report_flag_options": REPORT_FLAGS,
        },
        user=current_user,
        db=db,
    )


def _role_name(form) -> str:
    role = str(form.get("role", ROLE_USER))
    return role if role in (ROLE_ADMIN, ROLE_USER) else ROLE_USER


def _report_flags(form) -> dict[str, bool]:
    return {key: parse_bool(form.get(key)) for key, _ in REPORT_FLAGS}
