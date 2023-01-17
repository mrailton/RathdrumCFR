<?php

declare(strict_types=1);

namespace App\Http\Controllers\Users;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;

class ShowUserPermissionsController extends Controller
{
    public function __invoke(Request $request, User $user): View
    {
        $user->with('permissions');

        $permissions = Permission::orderBy('name')->get();

        return view('users.permissions', ['user' => $user, 'permissions' => $permissions]);
    }
}
