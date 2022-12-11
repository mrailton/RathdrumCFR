<?php

declare(strict_types=1);

namespace App\Http\Controllers\Defibs\Notes;

use App\Http\Controllers\Controller;
use App\Http\Requests\Defibs\Notes\StoreDefibNoteRequest;
use App\Models\Defib;
use App\Models\DefibNote;
use Illuminate\Http\RedirectResponse;

class StoreDefibNoteController extends Controller
{
    public function __invoke(StoreDefibNoteRequest $request, int $id): RedirectResponse
    {
        $defib = Defib::find($id);

        if (!$defib) {
            abort(404);
        }

        if (is_null(auth()->user())) {
            abort(401);
        }

        $note = new DefibNote($request->validated());
        $note->defib_id = $defib->id;
        $note->user_id = auth()->user()->id;
        $note->save();

        return redirect()->route('defibs.view', ['id' => $defib->id])->with('success', 'Defib note successfully added');
    }
}
