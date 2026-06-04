#!/usr/bin/env python3
"""
Import selected tables from the legacy Laravel MySQL database.

Preserves primary keys (upsert). Only imports tables that exist in the FastAPI schema.
Assign user roles manually in Admin → Users after importing users.

Usage:
  uv run python scripts/import_from_laravel.py --list
  uv run python scripts/import_from_laravel.py --tables users,members,defibs
  IMPORT_TABLES=users,members uv run python scripts/import_from_laravel.py
"""

from __future__ import annotations

import argparse
import os
import sys
from typing import Any

from sqlalchemy import MetaData, Table, create_engine, inspect
from sqlalchemy.dialects.mysql import insert as mysql_insert
from sqlalchemy.dialects.sqlite import insert as sqlite_insert
from sqlalchemy.engine import Engine
from sqlalchemy.orm import Session

from app.core.config import get_settings
from app.core.database import get_session_factory, init_db
from app.core.time import utc_now

# FK-safe order
TABLE_ORDER = [
    "users",
    "members",
    "member_notes",
    "defibs",
    "defib_notes",
    "defib_inspections",
    "ampds_codes",
    "callouts",
    "callout_member",
    "training_sessions",
    "member_training_session",
    "user_reports",
]

SKIP_TABLES = frozenset(
    {
        "migrations",
        "failed_jobs",
        "password_reset_tokens",
        "personal_access_tokens",
        "queue_monitors",
        "health_check_result_history_items",
        "permissions",
        "model_has_permissions",
        "role_has_permissions",
        "roles",
        "model_has_roles",
    }
)


def _upsert_stmt(dialect_name: str, table: Table, row: dict[str, Any]):
    if dialect_name == "mysql":
        stmt = mysql_insert(table).values(**row)
        update_cols = {k: stmt.inserted[k] for k in row if k != "id"}
        return stmt.on_duplicate_key_update(**update_cols)
    if dialect_name == "sqlite":
        stmt = sqlite_insert(table).values(**row)
        update_cols = {k: stmt.excluded[k] for k in row if k != "id"}
        return stmt.on_conflict_do_update(index_elements=["id"], set_=update_cols)
    return None


def import_table(
    source: Engine,
    target_session: Session,
    table_name: str,
    target_meta: MetaData,
) -> int:
    if table_name in SKIP_TABLES or table_name not in target_meta.tables:
        return 0

    src_meta = MetaData()
    if table_name not in inspect(source).get_table_names():
        return 0

    src_table = Table(table_name, src_meta, autoload_with=source)
    tgt_table = target_meta.tables[table_name]
    dialect = target_session.bind.dialect.name  # type: ignore[union-attr]

    rows = source.connect().execute(src_table.select()).mappings().all()
    count = 0
    for raw in rows:
        row = {k: raw[k] for k in raw.keys() if k in tgt_table.c}
        if dialect in ("mysql", "sqlite") and "id" in row:
            stmt = _upsert_stmt(dialect, tgt_table, row)
            if stmt is not None:
                target_session.execute(stmt)
                count += 1
                continue
        existing = target_session.get(tgt_table, row.get("id")) if "id" in row else None
        if existing:
            for k, v in row.items():
                setattr(existing, k, v)
        else:
            target_session.execute(tgt_table.insert().values(**row))
        count += 1
    return count


def resolve_tables(requested: list[str] | None) -> list[str]:
    if not requested:
        return []
    known = set(TABLE_ORDER)
    ordered = [t for t in TABLE_ORDER if t in requested]
    unknown = [t for t in requested if t not in known and t not in SKIP_TABLES]
    if unknown:
        print(f"Unknown tables (skipped): {', '.join(unknown)}", file=sys.stderr)
    return ordered


def run_import(tables: list[str]) -> None:
    settings = get_settings()
    if not settings.laravel_database_url:
        print("LARAVEL_DATABASE_URL is not set.", file=sys.stderr)
        sys.exit(1)

    if not tables:
        print("No tables selected. Use --tables or IMPORT_TABLES. Run with --list for options.", file=sys.stderr)
        sys.exit(1)

    init_db(settings)
    source_engine = create_engine(settings.laravel_database_url, pool_pre_ping=True)
    target_session = get_session_factory()()

    from app.models.base import Base

    target_meta = Base.metadata
    totals: dict[str, int] = {}
    started = utc_now()

    try:
        for table_name in tables:
            n = import_table(source_engine, target_session, table_name, target_meta)
            if n:
                totals[table_name] = n
                print(f"  {table_name}: {n} rows")
            else:
                print(f"  {table_name}: (no rows or not in source)")

        target_session.commit()
        elapsed = (utc_now() - started).total_seconds()
        print(f"Import complete in {elapsed:.1f}s — {sum(totals.values())} rows across {len(totals)} tables.")
    except Exception:
        target_session.rollback()
        raise
    finally:
        target_session.close()
        source_engine.dispose()


def main() -> None:
    parser = argparse.ArgumentParser(description="Import selected tables from Laravel MySQL")
    parser.add_argument(
        "--tables",
        "-t",
        help="Comma-separated table names to import (e.g. users,members,defibs)",
    )
    parser.add_argument("--list", action="store_true", help="List importable tables and exit")
    args = parser.parse_args()

    if args.list:
        print("Importable tables (in FK order):")
        for name in TABLE_ORDER:
            print(f"  {name}")
        print("\nSkipped Laravel-only tables:", ", ".join(sorted(SKIP_TABLES)))
        print("\nNote: assign roles under Admin → Users after importing users.")
        return

    env_tables = os.environ.get("IMPORT_TABLES", "").strip()
    raw = args.tables or env_tables
    requested = [t.strip() for t in raw.split(",") if t.strip()] if raw else None
    run_import(resolve_tables(requested))


if __name__ == "__main__":
    main()
