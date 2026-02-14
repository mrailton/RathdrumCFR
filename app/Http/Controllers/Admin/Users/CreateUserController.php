<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin\Users;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Spatie\Permission\Models\Role;

final class CreateUserController extends Controller
{
    public function __invoke(): View
    {
        $this->authorize('create', User::class);
        $roles = Role::all();

        return view('admin.users.create', compact('roles'));
    }
}
