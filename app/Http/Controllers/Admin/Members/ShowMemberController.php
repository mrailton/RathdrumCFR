<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin\Members;

use App\Http\Controllers\Controller;
use App\Models\Member;
use Illuminate\Contracts\View\View;

final class ShowMemberController extends Controller
{
    public function __invoke(Member $member): View
    {
        $this->authorize('view', $member);
        $member->load(['notes', 'callouts', 'trainingSessions']);

        return view('admin.members.show', compact('member'));
    }
}
