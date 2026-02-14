<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin\Members;

use App\Http\Controllers\Controller;
use App\Models\Member;
use Illuminate\Contracts\View\View;

final class EditMemberController extends Controller
{
    public function __invoke(Member $member): View
    {
        $this->authorize('update', $member);
        return view('admin.members.edit', compact('member'));
    }
}
