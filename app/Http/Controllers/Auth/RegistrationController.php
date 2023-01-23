<?php

declare(strict_types=1);

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Invite;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class RegistrationController extends Controller
{
    public function __invoke(Request $request, Invite $invite): View
    {
        if ($invite->expires_at < now()) {
            abort(404);
        }

        return view('auth.register', ['invite' => $invite]);
    }
}
