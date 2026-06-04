from datetime import datetime

from fastapi import APIRouter, Form, Request
from fastapi.responses import RedirectResponse

from app.core.database import mark_for_commit
from app.dependencies import CurrentUser, DbSession, verify_csrf
from app.routers.admin._helpers import require_admin
from app.services import members as member_service
from app.templating import flash, render

router = APIRouter(prefix="/members", tags=["admin.members"])

STATUSES = ["inactive", "active", "new recruit", "buddying"]


@router.get("", name="admin.members.index")
def index(request: Request, db: DbSession, user: CurrentUser):
    require_admin(db, user)
    return render(request, "admin/members/index.html", {"members": member_service.list_members(db)}, user=user, db=db)


@router.get("/create", name="admin.members.create")
def create_form(request: Request, db: DbSession, user: CurrentUser):
    require_admin(db, user)
    return render(request, "admin/members/form.html", {"member": None, "statuses": STATUSES}, user=user, db=db)


@router.post("/create", name="admin.members.store")
async def store(request: Request, db: DbSession, user: CurrentUser, csrf_token: str = Form("")):
    require_admin(db, user)
    verify_csrf(request, csrf_token)
    form = await request.form()
    data = _member_payload(form)
    member_service.create_member(db, data, user.id)
    mark_for_commit(db)
    flash(request, "success", "Member created.")
    return RedirectResponse(url="/admin/members", status_code=303)


@router.get("/{member_id}", name="admin.members.show")
def show(member_id: int, request: Request, db: DbSession, user: CurrentUser):
    require_admin(db, user)
    member = member_service.get_member(db, member_id)
    if not member:
        return RedirectResponse("/admin/members", status_code=303)
    return render(request, "admin/members/show.html", {"member": member}, user=user, db=db)


@router.get("/{member_id}/edit", name="admin.members.edit")
def edit_form(member_id: int, request: Request, db: DbSession, user: CurrentUser):
    require_admin(db, user)
    member = member_service.get_member(db, member_id)
    if not member:
        return RedirectResponse("/admin/members", status_code=303)
    return render(request, "admin/members/form.html", {"member": member, "statuses": STATUSES}, user=user, db=db)


@router.post("/{member_id}/edit", name="admin.members.update")
async def update(member_id: int, request: Request, db: DbSession, user: CurrentUser, csrf_token: str = Form("")):
    require_admin(db, user)
    verify_csrf(request, csrf_token)
    member = member_service.get_member(db, member_id)
    if not member:
        return RedirectResponse("/admin/members", status_code=303)
    form = await request.form()
    member_service.update_member(db, member, _member_payload(form))
    mark_for_commit(db)
    flash(request, "success", "Member updated.")
    return RedirectResponse(f"/admin/members/{member_id}", status_code=303)


@router.post("/{member_id}/notes", name="admin.members.notes.store")
async def add_note(
    member_id: int, request: Request, db: DbSession, user: CurrentUser, csrf_token: str = Form(""), note: str = Form("")
):
    require_admin(db, user)
    verify_csrf(request, csrf_token)
    member_service.add_member_note(db, member_id, note, user.id)
    mark_for_commit(db)
    return RedirectResponse(f"/admin/members/{member_id}", status_code=303)


@router.post("/{member_id}/delete", name="admin.members.delete")
def delete(member_id: int, request: Request, db: DbSession, user: CurrentUser, csrf_token: str = Form("")):
    require_admin(db, user)
    verify_csrf(request, csrf_token)
    member = member_service.get_member(db, member_id)
    if member:
        member_service.soft_delete_member(db, member)
        mark_for_commit(db)
    return RedirectResponse("/admin/members", status_code=303)


def _member_payload(form) -> dict:
    def d(key: str):
        v = form.get(key)
        if not v:
            return None
        try:
            return datetime.strptime(str(v), "%Y-%m-%d").date()
        except ValueError:
            return str(v)

    return {
        "name": str(form.get("name", "")),
        "status": str(form.get("status", "inactive")),
        "title": str(form.get("title", "Responder")),
        "phone": form.get("phone") or None,
        "email": form.get("email") or None,
        "address_1": form.get("address_1") or None,
        "address_2": form.get("address_2") or None,
        "eircode": form.get("eircode") or None,
        "cfr_certificate_number": form.get("cfr_certificate_number") or None,
        "cfr_certificate_expiry": d("cfr_certificate_expiry"),
        "volunteer_declaration": d("volunteer_declaration"),
        "garda_vetting_id": form.get("garda_vetting_id") or None,
        "garda_vetting_date": d("garda_vetting_date"),
        "cism_completed": d("cism_completed"),
        "child_first_completed": d("child_first_completed"),
        "ppe_community_completed": d("ppe_community_completed"),
        "ppe_acute_completed": d("ppe_acute_completed"),
        "hand_hygiene_completed": d("hand_hygiene_completed"),
        "hiqa_completed": d("hiqa_completed"),
        "covid_return_completed": d("covid_return_completed"),
        "ppe_assessment_completed": d("ppe_assessment_completed"),
    }
