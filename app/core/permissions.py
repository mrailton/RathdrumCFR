"""Backward-compatible shim; use app.policies for authorization."""

from app.policies import get_role_names, is_admin, is_operator

__all__ = ["get_role_names", "is_admin", "is_operator"]
