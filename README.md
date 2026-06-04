# Rathdrum CFR (FastAPI)

Community First Response operations platform, rebuilt from the [Laravel/Filament app](https://github.com/mrailton/rathdrumcfr) using FastAPI, Jinja2, Tailwind CSS 4, DaisyUI, and Alpine.js. Architecture follows [MedRelay](https://github.com/mrailton/medrelay).

## Requirements

- Python 3.14
- [uv](https://docs.astral.sh/uv/) for packages and virtualenv
- Node.js 20+ for frontend assets

## Quick start

```bash
cp .env.example .env
uv sync
npm install
npm run build
uv run alembic upgrade head
make server
```

Visit http://127.0.0.1:8000 (public site) and http://127.0.0.1:8000/admin/login (admin).

Create an initial user (after import, or for a fresh install):

```bash
uv run rathdrumcfr user-create "Admin" admin@example.com 'your-password' --role admin
```

**Roles:** `user` (callouts + defibs, no delete) and `admin` (everything). Laravel `super_admin` is treated as admin. Assign roles under **Admin → Users**.

**Database:** Production must use MySQL (`DATABASE_URL=mysql+pymysql://...`). SQLite is only suitable for automated tests.

**Scheduled reports:** When `SCHEDULER_ENABLED=true` (default), the app sends monthly report emails on the 1st at 09:00 (`APP_TIMEZONE`). Disable and use cron + `uv run rathdrumcfr reports-*` if you prefer external scheduling.

## Import from Laravel

Point `LARAVEL_DATABASE_URL` at the legacy database and `DATABASE_URL` at the new database, then migrate:

```bash
uv run alembic upgrade head
```

Import **only the tables you need** (preserves IDs, safe to re-run):

```bash
uv run python scripts/import_from_laravel.py --list
uv run python scripts/import_from_laravel.py --tables users,members,defibs,callouts
```

Importing `users` maps legacy Spatie role assignments onto `users.role`. Permission and role pivot tables are not created in the new schema.

## Scripts

| Command | Description |
|---------|-------------|
| `make server` | Dev server (uvicorn reload) |
| `make assets` | Build CSS/JS |
| `make migrate` | Alembic upgrade |
| `make import` | Laravel → FastAPI data import |
| `make test` | Pytest |
| `uv run rathdrumcfr reports-cert-expiry` | Scheduled report (see CLI) |

## Project layout

```
app/
  core/          config, database, security, scheduler
  models/        SQLAlchemy models
  schemas/       Pydantic form models
  services/      Business logic
  routers/       HTTP routes (public + admin)
  templates/     Jinja2 (mobile-first admin drawer)
  static/dist/   Built assets (Vite)
alembic/         Database migrations
scripts/         import_from_laravel.py
```

## Roles

Two roles only: **user** (callouts + defibs, no delete) and **admin** (full access). Laravel `super_admin` counts as admin. Assign roles under **Admin → Users**.
