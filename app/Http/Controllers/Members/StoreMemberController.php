<?php

declare(strict_types=1);

namespace App\Http\Controllers\Members;

use App\Http\Controllers\Controller;
use App\Http\Requests\Members\StoreMemberRequest;
use App\Models\Member;
use Illuminate\Http\RedirectResponse;

class StoreMemberController extends Controller
{
    public function __invoke(StoreMemberRequest $request): RedirectResponse
    {
        if (is_null(auth()->user())) {
            abort(401);
        }

        $member = new Member($request->validated());
        $member->user_id = auth()->user()->id;
        $member->save();

        return redirect()->route('members.list')->with('success', 'New member successfully added');
    }
}
