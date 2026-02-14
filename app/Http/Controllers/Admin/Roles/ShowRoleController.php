<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin\Roles;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\View\View;
use Spatie\Permission\Models\Role;

final class ShowRoleController extends Controller
{
    public function __invoke(Role $role): View
    {
        $this->authorize('view', $role);

        $role->load(['permissions', 'users']);

        return view('admin.roles.show', compact('role'));
    }
}
