from fastapi import APIRouter, Depends

from app.dependencies import require_auth
from app.routers.admin import ampds, callouts, dashboard, defibs, members, training, users

router = APIRouter(prefix="/admin", tags=["admin"], dependencies=[Depends(require_auth)])
router.include_router(dashboard.router)
router.include_router(callouts.router)
router.include_router(defibs.router)
router.include_router(members.router)
router.include_router(training.router)
router.include_router(users.router)
router.include_router(ampds.router)
