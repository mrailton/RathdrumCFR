<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\Auth\StoreRegistrationRequest;
use App\Models\Invite;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Hash;

class RegistrationController extends Controller
{
    public function create(Invite $invite): View
    {
        if ($invite->expires_at < now()) {
            abort(404);
        }

        return view('auth.register', ['invite' => $invite]);
    }

    public function store(StoreRegistrationRequest $request): RedirectResponse
    {
        $invite = Invite::query()->where('token', '=', $request->get('token'))->where('expires_at', '>=', now())->first();

        if (! $invite) {
            abort(400);
        }

        $invite->registered_at = now();
        $invite->expires_at = now();
        $invite->save();

        $user = new User();
        $user->name = $request->get('name');
        $user->email = $request->get('email');
        $user->password = Hash::make($request->get('password'));
        $user->save();

        $user->givePermissionTo(['defib.list', 'defib.view', 'defib.note', 'defib.inspect']);

        return redirect()->route('login.create')->success('You are now registered and can login');
    }
}
