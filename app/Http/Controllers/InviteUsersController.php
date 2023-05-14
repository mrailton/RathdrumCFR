<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\Users\StoreUserInvitationRequest;
use App\Mail\UserInvitationMail;
use App\Models\Invite;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Mail;

class InviteUsersController extends Controller
{
    public function create(): View
    {
        return view('users.invite');
    }

    public function store(StoreUserInvitationRequest $request): RedirectResponse
    {
        $invite = new Invite($request->validated());
        $invite->user_id = auth()->user()->id;
        $invite->token = substr(md5(rand(0, 9).$invite->email.time()), 0, 32);
        $invite->expires_at = now()->addHours(48);
        $invite->save();

        Mail::to($invite->email)->queue(new UserInvitationMail($invite));

        return redirect()->route('users.list')->success('User Successfully Invited');
    }
}
