<?php

declare(strict_types=1);

namespace App\Http\Controllers\Defibs;

use App\Http\Controllers\Controller;
use App\Http\Requests\Defibs\UpsertDefibRequest;
use App\Models\Defib;
use Illuminate\Http\RedirectResponse;

class UpdateDefibController extends Controller
{
    public function __invoke(UpsertDefibRequest $request, int $id): RedirectResponse
    {
        $defib = Defib::find($id);

        if (!$defib) {
            abort(404);
        }

        $defib->update($request->validated());

        return redirect()->route('defibs.view', ['id' => $defib->id])->with('success', 'Defib successfully updated');
    }
}
