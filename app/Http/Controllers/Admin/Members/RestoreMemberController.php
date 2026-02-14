<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin\Members;

use App\Http\Controllers\Controller;
use App\Models\Member;
use Illuminate\Http\RedirectResponse;

final class RestoreMemberController extends Controller
{
    public function __invoke(int $member): RedirectResponse
    {
        $member = Member::onlyTrashed()->findOrFail($member);
        $this->authorize('restore', $member);
        $member->restore();

        return redirect()
            ->route('admin.members.index')
            ->with('success', 'Member restored successfully.');
    }
}
