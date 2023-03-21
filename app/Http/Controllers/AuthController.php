<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\Auth\StoreLoginRequest;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class AuthController extends Controller
{
    public function create(Request $request): View
    {
        return view('auth.login');
    }

    public function store(StoreLoginRequest $request): RedirectResponse
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

    public function destroy(Request $request): RedirectResponse
    {
        Auth::logout();

        Session::regenerate();

        return redirect()->route('index')->with('Success', 'You have been successfully logged out');
    }
}
