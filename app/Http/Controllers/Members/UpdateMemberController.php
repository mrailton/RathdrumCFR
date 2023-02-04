<?php

declare(strict_types=1);

namespace App\Http\Controllers\Members;

use App\Http\Controllers\Controller;
use App\Http\Requests\Members\UpdateMemberRequest;
use App\Models\Member;
use Illuminate\Http\RedirectResponse;

class UpdateMemberController extends Controller
{
    public function __invoke(UpdateMemberRequest $request, Member $member): RedirectResponse
    {
        $member->update($request->validated());

        return redirect()->route('members.view', ['member' => $member])->with('success', 'Member successfully updated');
    }
}
