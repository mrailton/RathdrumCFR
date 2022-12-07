<?php

declare(strict_types=1);

namespace App\Http\Controllers\Members;

use App\Models\Member;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use App\Http\Requests\Members\StoreMemberRequest;

class StoreMemberController extends Controller
{
    public function __invoke(StoreMemberRequest $request): RedirectResponse
    {
        $member = new Member($request->validated());
        $member->user_id = auth()->id();
        $member->save();

        return redirect()->route('members.list')->with('success', 'New member successfully added');
    }
}
