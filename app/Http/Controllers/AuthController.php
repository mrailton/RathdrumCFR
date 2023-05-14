<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\Auth\StoreLoginRequest;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use function toast;

class AuthController extends Controller
{
    public function create(): View
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

        toast()->success('You have successfully logged in')->pushOnNextPage();

        return redirect()->intended('/');
    }

    public function destroy(): RedirectResponse
    {
        Auth::logout();

        Session::regenerate();

        toast()->success('You have been successfully logged out')->pushOnNextPage();

        return redirect()->route('index');
    }
}
