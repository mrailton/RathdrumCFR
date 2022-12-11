<?php

declare(strict_types=1);

namespace App\Http\Controllers\Members;

use App\Http\Controllers\Controller;
use App\Models\Member;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class EditMemberController extends Controller
{
    public function __invoke(Request $request, int $id): View
    {
        $member = Member::find($id);

        return view('members.update', ['member' => $member]);
    }
}
