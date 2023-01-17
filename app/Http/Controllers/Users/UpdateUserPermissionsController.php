<?php

declare(strict_types=1);

namespace App\Http\Controllers\Users;

use App\Http\Controllers\Controller;
use App\Http\Requests\Users\UpdateUserPermissionsRequest;
use App\Models\User;
use Illuminate\Http\RedirectResponse;

class UpdateUserPermissionsController extends Controller
{
    public function __invoke(UpdateUserPermissionsRequest $request, User $user): RedirectResponse
    {
        $user->permissions()->sync($request->get('permissions'));

        return redirect()->route('users.show', ['user' => $user])->with('success', 'User Permissions Successfully Updated');
    }
}
