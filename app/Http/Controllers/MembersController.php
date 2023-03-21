<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\Members\StoreMemberRequest;
use App\Http\Requests\Members\UpdateMemberRequest;
use App\Models\Member;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class MembersController extends Controller
{
    public function list(Request $request): View
    {
        $members = Member::paginate(10);

        return view('members.list', ['members' => $members]);
    }

    public function create(Request $request): View
    {
        return view('members.create');
    }

    public function store(StoreMemberRequest $request): RedirectResponse
    {
        $member = new Member($request->validated());
        $member->user_id = auth()->user()->id;
        $member->save();

        return redirect()->route('members.list')->with('success', 'New member successfully added');
    }

    public function show(Request $request, Member $member): View
    {
        $member->load(['notes.author']);

        return view('members.view', ['member' => $member]);
    }

    public function edit(Request $request, Member $member): View
    {
        return view('members.update', ['member' => $member]);
    }

    public function update(UpdateMemberRequest $request, Member $member): RedirectResponse
    {
        $member->update($request->validated());

        return redirect()->route('members.view', ['member' => $member])->with('success', 'Member successfully updated');
    }
}
