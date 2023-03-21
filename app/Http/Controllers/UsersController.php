<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Invite;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class UsersController extends Controller
{
    public function list(Request $request): View
    {
        $users = User::paginate(10);
        $invites = Invite::all();

        return view('users.list', ['users' => $users, 'invites' => $invites]);
    }

    public function show(Request $request, User $user): View
    {
        return view('users.show', ['user' => $user]);
    }
}
