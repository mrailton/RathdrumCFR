from datetime import datetime

from fastapi import APIRouter, Form, Request
from fastapi.responses import RedirectResponse

from app.core.database import mark_for_commit
from app.dependencies import CurrentUser, DbSession, verify_csrf
from app.routers.admin._helpers import form_str, parse_bool, require_admin, require_operator
from app.services import defibs as defib_service
from app.services import members as member_service
from app.services import reports as report_service
from app.templating import flash, render

router = APIRouter(prefix="/defibs", tags=["admin.defibs"])


@router.get("", name="admin.defibs.index")
def index(request: Request, db: DbSession, user: CurrentUser):
    require_operator(db, user)
    return render(request, "admin/defibs/index.html", {"defibs": defib_service.list_defibs(db)}, user=user, db=db)


@router.get("/create", name="admin.defibs.create")
def create_form(request: Request, db: DbSession, user: CurrentUser):
    require_operator(db, user)
    return render(request, "admin/defibs/form.html", {"defib": None}, user=user, db=db)


@router.post("/create", name="admin.defibs.store")
async def store(request: Request, db: DbSession, user: CurrentUser, csrf_token: str = Form("")):
    require_operator(db, user)
    verify_csrf(request, csrf_token)
    form = await request.form()
    defib_service.create_defib(db, _defib_payload(form), user.id)
    mark_for_commit(db)
    flash(request, "success", "Defibrillator created.")
    return RedirectResponse("/admin/defibs", status_code=303)


@router.get("/{defib_id}", name="admin.defibs.show")
def show(defib_id: int, request: Request, db: DbSession, user: CurrentUser):
    require_operator(db, user)
    defib = defib_service.get_defib(db, defib_id)
    if not defib:
        return RedirectResponse("/admin/defibs", status_code=303)
    return render(
        request,
        "admin/defibs/show.html",
        {"defib": defib, "members": member_service.list_members(db)},
        user=user,
        db=db,
    )


@router.get("/{defib_id}/edit", name="admin.defibs.edit")
def edit_form(defib_id: int, request: Request, db: DbSession, user: CurrentUser):
    require_operator(db, user)
    defib = defib_service.get_defib(db, defib_id)
    if not defib:
        return RedirectResponse("/admin/defibs", status_code=303)
    return render(request, "admin/defibs/form.html", {"defib": defib}, user=user, db=db)


@router.post("/{defib_id}/edit", name="admin.defibs.update")
async def update(defib_id: int, request: Request, db: DbSession, user: CurrentUser, csrf_token: str = Form("")):
    require_operator(db, user)
    verify_csrf(request, csrf_token)
    defib = defib_service.get_defib(db, defib_id)
    if not defib:
        return RedirectResponse("/admin/defibs", status_code=303)
    form = await request.form()
    defib_service.update_defib(db, defib, _defib_payload(form))
    mark_for_commit(db)
    return RedirectResponse(f"/admin/defibs/{defib_id}", status_code=303)


@router.post("/{defib_id}/inspections", name="admin.defibs.inspections.store")
async def add_inspection(defib_id: int, request: Request, db: DbSession, user: CurrentUser, csrf_token: str = Form("")):
    require_operator(db, user)
    verify_csrf(request, csrf_token)
    defib = defib_service.get_defib(db, defib_id)
    if not defib:
        return RedirectResponse("/admin/defibs", status_code=303)
    form = await request.form()
    defib_service.create_inspection(
        db,
        defib,
        {
            "member_id": int(form_str(form.get("member_id"))),
            "inspected_at": _dt(form.get("inspected_at")),
            "pads_expire_at": _dt(form.get("pads_expire_at")),
            "battery_expires_at": _dt(form.get("battery_expires_at")),
            "battery_condition": form.get("battery_condition") or None,
            "notes": form.get("notes") or None,
        },
        user.id,
    )
    mark_for_commit(db)
    report_service.notify_defib_inspected(db, defib)
    mark_for_commit(db)
    return RedirectResponse(f"/admin/defibs/{defib_id}", status_code=303)


@router.post("/{defib_id}/notes", name="admin.defibs.notes.store")
async def add_note(
    defib_id: int, request: Request, db: DbSession, user: CurrentUser, csrf_token: str = Form(""), note: str = Form("")
):
    require_operator(db, user)
    verify_csrf(request, csrf_token)
    defib_service.add_defib_note(db, defib_id, note, user.id)
    mark_for_commit(db)
    return RedirectResponse(f"/admin/defibs/{defib_id}", status_code=303)


@router.post("/{defib_id}/delete", name="admin.defibs.delete")
def delete(defib_id: int, request: Request, db: DbSession, user: CurrentUser, csrf_token: str = Form("")):
    require_admin(db, user)
    verify_csrf(request, csrf_token)
    defib = defib_service.get_defib(db, defib_id)
    if defib:
        defib_service.soft_delete_defib(db, defib)
        mark_for_commit(db)
    return RedirectResponse("/admin/defibs", status_code=303)


@router.post("/{defib_id}/restore", name="admin.defibs.restore")
def restore(defib_id: int, request: Request, db: DbSession, user: CurrentUser, csrf_token: str = Form("")):
    require_admin(db, user)
    verify_csrf(request, csrf_token)
    defib = defib_service.get_defib(db, defib_id)
    if defib:
        defib_service.restore_defib(db, defib)
        mark_for_commit(db)
    return RedirectResponse("/admin/defibs", status_code=303)


def _dt(value) -> datetime:
    return datetime.fromisoformat(str(value).replace("Z", ""))


def _defib_payload(form) -> dict:
    def optional_dt(key):
        v = form.get(key)
        return _dt(v) if v else None

    return {
        "name": form.get("name") or None,
        "location": str(form.get("location", "")),
        "coordinates": form.get("coordinates") or None,
        "display_on_map": parse_bool(form.get("display_on_map")),
        "model": str(form.get("model", "")),
        "serial": form.get("serial") or None,
        "owner": str(form.get("owner", "RathdrumCFR")),
        "defib_lot_number": form.get("defib_lot_number") or None,
        "defib_manufacture_date": form.get("defib_manufacture_date") or None,
        "battery_lot_number": form.get("battery_lot_number") or None,
        "battery_manufacture_date": form.get("battery_manufacture_date") or None,
        "pads_lot_number": form.get("pads_lot_number") or None,
        "pads_manufacture_date": form.get("pads_manufacture_date") or None,
        "last_serviced_at": optional_dt("last_serviced_at"),
        "last_inspected_at": optional_dt("last_inspected_at"),
        "last_inspected_by": form.get("last_inspected_by") or None,
        "pads_expire_at": optional_dt("pads_expire_at"),
        "battery_expires_at": optional_dt("battery_expires_at"),
    }
