from datetime import datetime

from fastapi import APIRouter, Form, Request
from fastapi.responses import RedirectResponse

from app.core.database import mark_for_commit
from app.dependencies import CurrentUser, DbSession, verify_csrf
from app.routers.admin._helpers import require_admin
from app.services import members as member_service
from app.services import training as training_service
from app.templating import flash, render

router = APIRouter(prefix="/training", tags=["admin.training"])


@router.get("", name="admin.training.index")
def index(request: Request, db: DbSession, user: CurrentUser):
    require_admin(db, user)
    return render(
        request,
        "admin/training/index.html",
        {"sessions": training_service.list_training_sessions(db)},
        user=user,
        db=db,
    )


@router.get("/create", name="admin.training.create")
def create_form(request: Request, db: DbSession, user: CurrentUser):
    require_admin(db, user)
    return _form(request, db, user, None)


@router.post("/create", name="admin.training.store")
async def store(request: Request, db: DbSession, user: CurrentUser, csrf_token: str = Form("")):
    require_admin(db, user)
    verify_csrf(request, csrf_token)
    form = await request.form()
    training_service.save_training_session(db, _payload(form), _member_ids(form))
    mark_for_commit(db)
    flash(request, "success", "Training session saved.")
    return RedirectResponse("/admin/training", status_code=303)


@router.get("/{session_id}/edit", name="admin.training.edit")
def edit_form(session_id: int, request: Request, db: DbSession, user: CurrentUser):
    require_admin(db, user)
    session = training_service.get_training_session(db, session_id)
    if not session:
        return RedirectResponse("/admin/training", status_code=303)
    return _form(request, db, user, session)


@router.post("/{session_id}/edit", name="admin.training.update")
async def update(session_id: int, request: Request, db: DbSession, user: CurrentUser, csrf_token: str = Form("")):
    require_admin(db, user)
    verify_csrf(request, csrf_token)
    form = await request.form()
    training_service.save_training_session(db, _payload(form), _member_ids(form), session_id=session_id)
    mark_for_commit(db)
    return RedirectResponse("/admin/training", status_code=303)


def _form(request, db, user, session):
    return render(
        request,
        "admin/training/form.html",
        {"session": session, "members": member_service.list_members(db)},
        user=user,
        db=db,
    )


def _payload(form) -> dict:
    return {
        "date": datetime.strptime(str(form.get("date")), "%Y-%m-%d").date(),
        "topic": str(form.get("topic", "")),
        "notes": form.get("notes") or None,
    }


def _member_ids(form) -> list[int]:
    return [int(x) for x in form.getlist("member_ids") if x]
