<?php

declare(strict_types=1);

namespace App\Http\Controllers\Defibs;

use App\Http\Controllers\Controller;
use App\Http\Requests\Defibs\UpsertDefibRequest;
use App\Models\Defib;
use Illuminate\Http\RedirectResponse;

class UpdateDefibController extends Controller
{
    public function __invoke(UpsertDefibRequest $request, Defib $defib): RedirectResponse
    {
        $defib->update($request->validated());

        return redirect()->route('defibs.view', ['defib' => $defib->id])->with('success', 'Defib successfully updated');
    }
}
