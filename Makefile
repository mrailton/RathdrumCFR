.PHONY: help install dev server assets assets-watch migrate import test lint

help:
	@grep -E '^[a-zA-Z_-]+:.*?## .*$$' $(MAKEFILE_LIST) | awk 'BEGIN {FS = ":.*?## "}; {printf "  %-18s %s\n", $$1, $$2}'

install: ## Sync Python (uv) and Node dependencies
	uv sync
	npm install

dev: server ## Alias for dev server

server: ## Run FastAPI with reload
	uv run uvicorn web:app --reload --host 127.0.0.1 --port 8000

assets: ## Build Tailwind/DaisyUI/Alpine assets
	npm run build

assets-watch: ## Watch and rebuild frontend assets
	npm run dev

migrate: ## Apply Alembic migrations
	uv run alembic upgrade head

import: ## Import from Laravel (set TABLES=users,members,...)
	uv run python scripts/import_from_laravel.py --tables $(TABLES)

test: ## Run pytest
	uv run pytest

lint: ## Ruff check and format
	uv run ruff check app/ tests/ scripts/
	uv run ruff format --check app/ tests/ scripts/
