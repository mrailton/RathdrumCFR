<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\Members\Notes\StoreMemberNoteRequest;
use App\Models\Member;
use App\Models\MemberNote;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class MemberNotesController extends Controller
{
    public function create(Request $request, Member $member): View
    {
        return view('members.notes.create', ['member' => $member]);
    }

    public function store(StoreMemberNoteRequest $request, Member $member): RedirectResponse
    {
        $note = new MemberNote($request->validated());
        $note->member_id = $member->id;
        $note->user_id = auth()->user()->id;
        $note->save();

        return redirect()->route('members.view', ['member' => $member])->with('success', 'Member note successfully added');
    }
}
