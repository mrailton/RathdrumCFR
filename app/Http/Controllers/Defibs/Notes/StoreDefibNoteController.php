<?php

declare(strict_types=1);

namespace App\Http\Controllers\Defibs\Notes;

use App\Models\Defib;
use App\Models\DefibNote;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use App\Http\Requests\Defibs\Notes\StoreDefibNoteRequest;

class StoreDefibNoteController extends Controller
{
    public function __invoke(StoreDefibNoteRequest $request, int $id): RedirectResponse
    {
        $defib = Defib::find($id);

        $note = new DefibNote($request->validated());
        $note->defib_id = $defib->id;
        $note->user_id = auth()->user()->id;
        $note->save();

        return redirect()->route('defibs.view', ['id' => $defib->id])->with('success', 'Defib note successfully added');
    }
}
