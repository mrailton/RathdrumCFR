<?php

declare(strict_types=1);

namespace App\Http\Controllers\Members;

use App\Models\Member;
use Illuminate\Http\Request;
use Illuminate\Contracts\View\View;
use App\Http\Controllers\Controller;

class ListMembersController extends Controller
{
    public function __invoke(Request $request): View
    {
        $members = Member::all();

        return view('members.list', ['members' => $members]);
    }
}
