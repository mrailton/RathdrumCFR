<?php

declare(strict_types=1);

namespace App\Http\Controllers\Defibs;

use App\Models\Defib;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use App\Http\Requests\Defibs\UpsertDefibRequest;

class UpdateDefibController extends Controller
{
    public function __invoke(UpsertDefibRequest $request, int $id): RedirectResponse
    {
        $defib = Defib::find($id);

        $defib->update($request->validated());

        return redirect()->route('defibs.view', ['id' => $defib->id]);
    }
}
