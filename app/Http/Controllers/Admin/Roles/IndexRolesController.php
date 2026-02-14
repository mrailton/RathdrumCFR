<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin\Roles;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;

final class IndexRolesController extends Controller
{
    public function __invoke(Request $request): View
    {
        $this->authorize('viewAny', Role::class);

        $query = Role::query()->withCount('users');

        if ($search = $request->get('search')) {
            $query->where('name', 'like', "%{$search}%");
        }

        $roles = $query->orderBy('name')->paginate(15)->withQueryString();

        return view('admin.roles.index', compact('roles'));
    }
}
