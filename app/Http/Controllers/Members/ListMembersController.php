<?php

declare(strict_types=1);

namespace App\Http\Controllers\Members;

use App\Http\Controllers\Controller;
use App\Models\Member;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class ListMembersController extends Controller
{
    public function __invoke(Request $request): View
    {
        $members = Member::paginate(10);

        return view('members.list', ['members' => $members]);
    }
}
