from fastapi import APIRouter, Form, Request
from fastapi.responses import RedirectResponse

from app.core.database import mark_for_commit
from app.dependencies import CurrentUser, DbSession, verify_csrf
from app.routers.admin._helpers import parse_bool, require_admin
from app.services import ampds as ampds_service
from app.templating import render

router = APIRouter(prefix="/ampds", tags=["admin.ampds"])


@router.get("", name="admin.ampds.index")
def index(request: Request, db: DbSession, user: CurrentUser):
    require_admin(db, user)
    return render(request, "admin/ampds/index.html", {"codes": ampds_service.list_ampds_codes(db)}, user=user, db=db)


@router.post("", name="admin.ampds.store")
async def store(request: Request, db: DbSession, user: CurrentUser, csrf_token: str = Form("")):
    require_admin(db, user)
    verify_csrf(request, csrf_token)
    form = await request.form()
    ampds_service.save_ampds_code(
        db,
        {
            "code": str(form.get("code", "")),
            "description": str(form.get("description", "")),
            "arrest_code": parse_bool(form.get("arrest_code")),
            "far_code": parse_bool(form.get("far_code")),
        },
    )
    mark_for_commit(db)
    return RedirectResponse("/admin/ampds", status_code=303)


@router.post("/{code_id}/delete", name="admin.ampds.delete")
def delete(code_id: int, request: Request, db: DbSession, user: CurrentUser, csrf_token: str = Form("")):
    require_admin(db, user)
    verify_csrf(request, csrf_token)
    from app.models.ampds_code import AMPDSCode

    code = db.get(AMPDSCode, code_id)
    if code:
        ampds_service.delete_ampds_code(db, code)
        mark_for_commit(db)
    return RedirectResponse("/admin/ampds", status_code=303)
