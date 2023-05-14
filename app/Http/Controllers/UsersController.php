<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Invite;
use App\Models\User;
use Illuminate\Contracts\View\View;

class UsersController extends Controller
{
    public function list(): View
    {
        $users = User::paginate(10);
        $invites = Invite::all();

        return view('users.list', ['users' => $users, 'invites' => $invites]);
    }

    public function show(User $user): View
    {
        return view('users.show', ['user' => $user]);
    }
}
