from datetime import datetime

from fastapi import APIRouter, Request

from app.dependencies import CurrentUser, DbSession
from app.routers.admin._helpers import require_operator
from app.services import dashboard as dashboard_service
from app.templating import render

router = APIRouter()


@router.get("/", name="admin.dashboard")
def dashboard(request: Request, db: DbSession, user: CurrentUser):
    require_operator(db, user)
    date_from = request.query_params.get("from")
    date_to = request.query_params.get("to")
    df = datetime.fromisoformat(date_from) if date_from else datetime(datetime.utcnow().year, 1, 1)
    dt = datetime.fromisoformat(date_to) if date_to else datetime.utcnow()
    from app import policies

    ctx: dict = {
        "date_from": df.date().isoformat(),
        "date_to": dt.date().isoformat(),
        "callout_stats": dashboard_service.callout_stats(db, df, dt),
    }
    if policies.is_admin(db, user):
        ctx["member_stats"] = dashboard_service.member_stats(db)
        ctx["defib_stats"] = dashboard_service.defib_stats(db)
    return render(request, "admin/dashboard.html", ctx, user=user, db=db)
