from datetime import datetime

from app.models.callout import Callout
from app.models.defib import Defib
from app.models.member import Member
from app.services import dashboard as dashboard_service


def test_dashboard_stats(db_session, admin_user):
    db_session.add(
        Member(user_id=admin_user.id, name="Active Member", status="active"),
    )
    db_session.add(
        Defib(
            user_id=admin_user.id,
            location="Town Hall",
            model="Philips",
            display_on_map=True,
        ),
    )
    db_session.add(
        Callout(
            user_id=admin_user.id,
            incident_number="INC-1",
            incident_date=datetime(2026, 3, 1, 12, 0),
            ampds_code="09E1",
            gender="Male",
        ),
    )
    db_session.flush()

    members = dashboard_service.member_stats(db_session)
    assert members["total"] == 1
    assert members["active"] == 1

    defibs = dashboard_service.defib_stats(db_session)
    assert defibs["total"] == 1
    assert defibs["public"] == 1

    callouts = dashboard_service.callout_stats(
        db_session,
        datetime(2026, 1, 1),
        datetime(2026, 12, 31),
    )
    assert callouts["total"] >= 1
