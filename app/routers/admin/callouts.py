from datetime import datetime

from fastapi import APIRouter, Form, Request
from fastapi.responses import RedirectResponse

from app.core.database import mark_for_commit
from app.dependencies import CurrentUser, DbSession, verify_csrf
from app.routers.admin._helpers import parse_bool, require_admin, require_operator
from app.services import ampds as ampds_service
from app.services import callouts as callout_service
from app.services import dashboard as dashboard_service
from app.services import members as member_service
from app.templating import flash, render

router = APIRouter(prefix="/callouts", tags=["admin.callouts"])


@router.get("", name="admin.callouts.index")
def index(request: Request, db: DbSession, user: CurrentUser):
    require_operator(db, user)
    date_from = request.query_params.get("from")
    date_to = request.query_params.get("to")
    df = datetime.fromisoformat(date_from) if date_from else None
    dt = datetime.fromisoformat(date_to) if date_to else None
    return render(
        request,
        "admin/callouts/index.html",
        {
            "callouts": callout_service.list_callouts(db, date_from=df, date_to=dt),
            "stats": dashboard_service.callout_stats(db, df, dt),
        },
        user=user,
        db=db,
    )


@router.get("/create", name="admin.callouts.create")
def create_form(request: Request, db: DbSession, user: CurrentUser):
    require_operator(db, user)
    return _form(request, db, user, None)


@router.post("/create", name="admin.callouts.store")
async def store(request: Request, db: DbSession, user: CurrentUser, csrf_token: str = Form("")):
    require_operator(db, user)
    verify_csrf(request, csrf_token)
    form = await request.form()
    data, member_ids = _callout_payload(form)
    callout_service.create_callout(db, data, member_ids, user.id)
    mark_for_commit(db)
    flash(request, "success", "Callout created.")
    return RedirectResponse("/admin/callouts", status_code=303)


@router.get("/{callout_id}", name="admin.callouts.show")
def show(callout_id: int, request: Request, db: DbSession, user: CurrentUser):
    require_operator(db, user)
    callout = callout_service.get_callout(db, callout_id)
    if not callout:
        return RedirectResponse("/admin/callouts", status_code=303)
    return render(request, "admin/callouts/show.html", {"callout": callout}, user=user, db=db)


@router.get("/{callout_id}/edit", name="admin.callouts.edit")
def edit_form(callout_id: int, request: Request, db: DbSession, user: CurrentUser):
    require_operator(db, user)
    callout = callout_service.get_callout(db, callout_id)
    if not callout:
        return RedirectResponse("/admin/callouts", status_code=303)
    return _form(request, db, user, callout)


@router.post("/{callout_id}/edit", name="admin.callouts.update")
async def update(callout_id: int, request: Request, db: DbSession, user: CurrentUser, csrf_token: str = Form("")):
    require_operator(db, user)
    verify_csrf(request, csrf_token)
    callout = callout_service.get_callout(db, callout_id)
    if not callout:
        return RedirectResponse("/admin/callouts", status_code=303)
    form = await request.form()
    data, member_ids = _callout_payload(form)
    callout_service.update_callout(db, callout, data, member_ids)
    mark_for_commit(db)
    return RedirectResponse(f"/admin/callouts/{callout_id}", status_code=303)


@router.post("/{callout_id}/delete", name="admin.callouts.delete")
def delete(callout_id: int, request: Request, db: DbSession, user: CurrentUser, csrf_token: str = Form("")):
    require_admin(db, user)
    verify_csrf(request, csrf_token)
    callout = callout_service.get_callout(db, callout_id)
    if callout:
        callout_service.delete_callout(db, callout)
        mark_for_commit(db)
    return RedirectResponse("/admin/callouts", status_code=303)


def _form(request, db, user, callout):
    return render(
        request,
        "admin/callouts/form.html",
        {
            "callout": callout,
            "ampds_codes": ampds_service.list_ampds_codes(db),
            "members": member_service.list_members(db),
        },
        user=user,
        db=db,
    )


def _callout_payload(form):
    member_ids = [int(x) for x in form.getlist("member_ids") if x]
    data = {
        "incident_number": str(form.get("incident_number", "")),
        "incident_date": datetime.fromisoformat(str(form.get("incident_date"))),
        "ampds_code": str(form.get("ampds_code", "")),
        "age": form.get("age") or None,
        "gender": str(form.get("gender", "Unknown")),
        "mobilised": parse_bool(form.get("mobilised")),
        "medical_facility": parse_bool(form.get("medical_facility")),
        "attended": parse_bool(form.get("attended")),
        "ohca_at_scene": parse_bool(form.get("ohca_at_scene")),
        "bystander_cpr": parse_bool(form.get("bystander_cpr")),
        "rosc_achieved": parse_bool(form.get("rosc_achieved")),
        "patient_transported": parse_bool(form.get("patient_transported")),
        "source_of_aed": form.get("source_of_aed") or None,
        "number_of_shocks_given": int(form.get("number_of_shocks_given") or 0),
        "responders_at_scene": int(form.get("responders_at_scene") or 0),
        "ppe_kits_used": form.get("ppe_kits_used") or None,
        "waste_disposal": form.get("waste_disposal") or None,
        "notes": form.get("notes") or None,
    }
    return data, member_ids
