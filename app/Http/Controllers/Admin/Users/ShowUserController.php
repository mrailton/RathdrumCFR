<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin\Users;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Contracts\View\View;

final class ShowUserController extends Controller
{
    public function __invoke(User $user): View
    {
        $this->authorize('view', $user);
        $user->load('roles');

        return view('admin.users.show', compact('user'));
    }
}
