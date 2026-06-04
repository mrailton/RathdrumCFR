.PHONY: help install setup dev server assets assets-watch \
	db-upgrade db-downgrade db-current db-history db-revision migrate \
	import import-list user-create \
	test test-cov lint lint-fix typecheck clean

# --- variables (override on the command line) ---
MSG ?=
TABLES ?=
USER_NAME ?= Admin
USER_EMAIL ?= admin@example.com
USER_PASSWORD ?=
USER_ROLE ?= admin

help: ## Show available commands
	@echo "Setup & run"
	@grep -E '^(install|setup|dev|server|assets|assets-watch):.*?##' $(MAKEFILE_LIST) | awk 'BEGIN {FS = ":.*?## "}; {printf "  %-18s %s\n", $$1, $$2}'
	@echo ""
	@echo "Database"
	@grep -E '^db-.*:.*?##' $(MAKEFILE_LIST) | awk 'BEGIN {FS = ":.*?## "}; {printf "  %-18s %s\n", $$1, $$2}'
	@grep -E '^migrate:.*?##' $(MAKEFILE_LIST) | awk 'BEGIN {FS = ":.*?## "}; {printf "  %-18s %s\n", $$1, $$2}'
	@echo ""
	@echo "Data & users"
	@grep -E '^(import|import-list|user-create):.*?##' $(MAKEFILE_LIST) | awk 'BEGIN {FS = ":.*?## "}; {printf "  %-18s %s\n", $$1, $$2}'
	@echo ""
	@echo "Quality"
	@grep -E '^(test|test-cov|lint|lint-fix|typecheck|clean):.*?##' $(MAKEFILE_LIST) | awk 'BEGIN {FS = ":.*?## "}; {printf "  %-18s %s\n", $$1, $$2}'

# --- setup & run ---
install: ## Sync Python (uv) and Node dependencies
	uv sync
	npm install

setup: ## First-time setup: install, build assets, migrate DB
	$(MAKE) install
	$(MAKE) assets
	$(MAKE) db-upgrade

dev: ## Run dev server and watch assets (parallel)
	@$(MAKE) -j2 server assets-watch

server: ## Run FastAPI with reload
	uv run uvicorn web:app --reload --host 127.0.0.1 --port 8000

assets: ## Build Tailwind/DaisyUI/Alpine assets
	npm run build

assets-watch: ## Watch and rebuild frontend assets
	npm run dev

# --- database ---
db-upgrade: ## Apply all pending database migrations
	uv run alembic upgrade head

migrate: db-upgrade ## Alias for db-upgrade

db-downgrade: ## Roll back one migration
	uv run alembic downgrade -1

db-current: ## Show current migration revision
	uv run alembic current

db-history: ## List migration history
	uv run alembic history --verbose

db-revision: ## Create autogenerate migration (MSG="description" required)
	@if [ -z "$(MSG)" ]; then \
		echo 'Usage: make db-revision MSG="add members column"'; \
		exit 1; \
	fi
	uv run alembic revision --autogenerate -m "$(MSG)"

# --- data & users ---
import-list: ## List tables available for Laravel import
	uv run python scripts/import_from_laravel.py --list

import: ## Import from Laravel (TABLES=users,members,...)
	@if [ -z "$(TABLES)" ]; then \
		echo 'Usage: make import TABLES=users,members,defibs'; \
		echo 'Run: make import-list'; \
		exit 1; \
	fi
	uv run python scripts/import_from_laravel.py --tables $(TABLES)

user-create: ## Create a user (USER_NAME, USER_EMAIL, USER_PASSWORD, USER_ROLE)
	@if [ -z "$(USER_PASSWORD)" ]; then \
		echo 'Usage: make user-create USER_PASSWORD=secret [USER_EMAIL=... USER_NAME=... USER_ROLE=admin]'; \
		exit 1; \
	fi
	uv run rathdrumcfr user-create "$(USER_NAME)" "$(USER_EMAIL)" "$(USER_PASSWORD)" --role $(USER_ROLE)

# --- quality ---
test: ## Run pytest
	uv run pytest

test-cov: ## Run pytest with coverage report for app/
	uv run pytest --cov=app --cov-report=term-missing

lint: ## Ruff check and format (no writes)
	uv run ruff check app/ tests/ scripts/
	uv run ruff format --check app/ tests/ scripts/

lint-fix: ## Auto-fix Ruff issues and format
	uv run ruff check --fix app/ tests/ scripts/
	uv run ruff format app/ tests/ scripts/

typecheck: ## Run mypy on app/
	uv run mypy app/

clean: ## Remove build artifacts and caches
	rm -rf app/resources/static/dist .pytest_cache .coverage htmlcov
	find . -type d -name __pycache__ -not -path './.venv/*' -prune -exec rm -rf {} +
