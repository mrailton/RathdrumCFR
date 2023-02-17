<?php

declare(strict_types=1);

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\StoreLoginRequest;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;

class AuthenticateUserController extends Controller
{
    public function __invoke(StoreLoginRequest $request): RedirectResponse
    {
        if (! Auth::attempt($request->only(['email', 'password']))) {
            return back()->withErrors([
                'email' => 'The provided credentials do not match our records',
            ])->withInput();
        }

        $request->session()->regenerate();

        $user = User::find(auth()->user()->id);
        $user->last_login_at = now();
        $user->last_login_from = $request->ip();
        $user->save();

        return redirect()->intended('/')->with('success', 'You have successfully logged in');
    }
}
