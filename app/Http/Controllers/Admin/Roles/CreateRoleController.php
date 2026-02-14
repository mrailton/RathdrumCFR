<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin\Roles;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\View\View;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

final class CreateRoleController extends Controller
{
    public function __invoke(): View
    {
        $this->authorize('create', Role::class);

        $permissions = Permission::orderBy('name')->get()->groupBy(function ($permission) {
            $parts = explode('_', $permission->name, 3);

            return $parts[2] ?? $parts[1] ?? $permission->name;
        });

        return view('admin.roles.create', compact('permissions'));
    }
}
