<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin\Members;

use App\Http\Controllers\Controller;
use App\Models\Member;
use Illuminate\Http\RedirectResponse;

final class DestroyMemberController extends Controller
{
    public function __invoke(Member $member): RedirectResponse
    {
        $this->authorize('delete', $member);
        $member->delete();

        return redirect()
            ->route('admin.members.index')
            ->with('success', 'Member deleted successfully.');
    }
}
