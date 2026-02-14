<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin\Roles;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Spatie\Permission\Models\Role;

final class DestroyRoleController extends Controller
{
    public function __invoke(Role $role): RedirectResponse
    {
        $this->authorize('delete', $role);

        // Prevent deletion of Admin role
        if ('Admin' === $role->name) {
            return redirect()
                ->route('admin.roles.index')
                ->with('error', 'The Admin role cannot be deleted.');
        }

        $role->delete();

        return redirect()
            ->route('admin.roles.index')
            ->with('success', 'Role deleted successfully.');
    }
}
