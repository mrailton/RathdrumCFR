<?php

declare(strict_types=1);

namespace App\Http\Controllers\Members;

use App\Http\Controllers\Controller;
use App\Http\Requests\Members\UpdateMemberRequest;
use App\Models\Member;
use Illuminate\Http\RedirectResponse;

class UpdateMemberController extends Controller
{
    public function __invoke(UpdateMemberRequest $request, int $id): RedirectResponse
    {
        $member = Member::find($id);

        if (!$member) {
            abort(404);
        }

        $member->update($request->validated());

        return redirect()->route('members.view', ['id' => $id])->with('success', 'Member successfully updated');
    }
}
