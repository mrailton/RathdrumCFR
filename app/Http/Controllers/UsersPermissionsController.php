<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\Users\UpdateUserPermissionsRequest;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Spatie\Permission\Models\Permission;
use function toast;

class UsersPermissionsController extends Controller
{
    public function show(User $user): View
    {
        $user->with('permissions');

        $permissions = Permission::orderBy('name')->get();

        return view('users.permissions', ['user' => $user, 'permissions' => $permissions]);
    }

    public function update(UpdateUserPermissionsRequest $request, User $user): RedirectResponse
    {
        $user->permissions()->sync($request->get('permissions'));

        toast()->success('User Permissions Successfully Updated')->pushOnNextPage();

        return redirect()->route('users.show', ['user' => $user]);
    }
}
