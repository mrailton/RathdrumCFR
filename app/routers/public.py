from fastapi import APIRouter, Depends, Form, Request
from fastapi.responses import RedirectResponse
from sqlalchemy.orm import Session

from app.core.config import get_settings
from app.core.database import mark_for_commit
from app.dependencies import DbSession, verify_csrf
from app.schemas.forms import ContactForm, contact_form
from app.services import defibs as defib_service
from app.services import mail as mail_service
from app.services import recaptcha as recaptcha_service
from app.templating import flash, render

router = APIRouter(tags=["public"])


@router.get("/", name="public.index")
def index(request: Request, db: DbSession):
    settings = get_settings()
    public_defibs = defib_service.list_public_defibs(db)
    markers = []
    for d in public_defibs:
        if d.coordinates and "," in d.coordinates:
            lat, lng = [p.strip() for p in d.coordinates.split(",", 1)]
            markers.append({"name": d.name or d.location, "lat": lat, "lng": lng, "location": d.location})
    return render(
        request,
        "public/index.html",
        {"defibs": markers, "maps_key": settings.google_maps_api_key},
    )


@router.get("/contact", name="public.contact")
def contact_create(request: Request):
    settings = get_settings()
    return render(request, "public/contact.html", {"recaptcha_site_key": settings.recaptcha_site_key})


@router.post("/contact", name="public.contact.store")
async def contact_store(
    request: Request,
    db: DbSession,
    form: ContactForm = Depends(contact_form),
):
    verify_csrf(request, form.csrf_token)
    if not await recaptcha_service.verify_recaptcha(form.g_recaptcha_response):
        flash(request, "error", "reCAPTCHA verification failed.")
        return RedirectResponse(url="/contact", status_code=303)
    mail_service.send_contact_email(form.name, str(form.email), form.phone, form.message)
    mark_for_commit(db)
    flash(request, "success", "Thank you — your message has been sent.")
    return RedirectResponse(url="/", status_code=303)
