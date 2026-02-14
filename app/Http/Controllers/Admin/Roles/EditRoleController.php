<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin\Roles;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\View\View;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

final class EditRoleController extends Controller
{
    public function __invoke(Role $role): View
    {
        $this->authorize('update', $role);

        $permissions = Permission::orderBy('name')->get()->groupBy(function ($permission) {
            $parts = explode('_', $permission->name, 3);

            return $parts[2] ?? $parts[1] ?? $permission->name;
        });

        $rolePermissions = $role->permissions->pluck('id')->toArray();

        return view('admin.roles.edit', compact('role', 'permissions', 'rolePermissions'));
    }
}
