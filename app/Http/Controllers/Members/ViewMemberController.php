<?php

declare(strict_types=1);

namespace App\Http\Controllers\Members;

use App\Http\Controllers\Controller;
use App\Models\Member;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class ViewMemberController extends Controller
{
    public function __invoke(Request $request, Member $member): View
    {
        $member->load(['notes.author']);

        return view('members.view', ['member' => $member]);
    }
}
