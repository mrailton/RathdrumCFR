<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\Members\StoreMemberRequest;
use App\Http\Requests\Members\UpdateMemberRequest;
use App\Models\Member;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use function toast;

class MembersController extends Controller
{
    public function list(): View
    {
        $members = Member::paginate(10);

        return view('members.list', ['members' => $members]);
    }

    public function create(): View
    {
        return view('members.create');
    }

    public function store(StoreMemberRequest $request): RedirectResponse
    {
        $member = new Member($request->validated());
        $member->user_id = auth()->user()->id;
        $member->save();

        toast()->success('New member successfully added')->pushOnNextPage();

        return redirect()->route('members.list');
    }

    public function show(Member $member): View
    {
        $member->load(['notes.author']);

        return view('members.view', ['member' => $member]);
    }

    public function edit(Member $member): View
    {
        return view('members.update', ['member' => $member]);
    }

    public function update(UpdateMemberRequest $request, Member $member): RedirectResponse
    {
        $member->update($request->validated());

        toast()->success('Member successfully updated')->pushOnNextPage();

        return redirect()->route('members.view', ['member' => $member]);
    }
}
