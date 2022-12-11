<?php

declare(strict_types=1);

namespace App\Http\Controllers\Members\Notes;

use App\Http\Controllers\Controller;
use App\Http\Requests\Members\Notes\StoreMemberNoteRequest;
use App\Models\Member;
use App\Models\MemberNote;
use Illuminate\Http\RedirectResponse;

class StoreMemberNoteController extends Controller
{
    public function __invoke(StoreMemberNoteRequest $request, int $id): RedirectResponse
    {
        $member = Member::find($id);

        if (!$member) {
            abort(404);
        }

        if (is_null(auth()->user())) {
            abort(401);
        }

        $note = new MemberNote($request->validated());
        $note->member_id = $member->id;
        $note->user_id = auth()->user()->id;
        $note->save();

        return redirect()->route('members.view', ['id' => $member->id])->with('success', 'Member note successfully added');
    }
}
