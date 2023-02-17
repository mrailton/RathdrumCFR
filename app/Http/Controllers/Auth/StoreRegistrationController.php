<?php

declare(strict_types=1);

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\StoreRegistrationRequest;
use App\Models\Invite;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Hash;

class StoreRegistrationController extends Controller
{
    public function __invoke(StoreRegistrationRequest $request): RedirectResponse
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

        return redirect()->route('login.create')->with('success', 'You are now registered and can login');
    }
}
