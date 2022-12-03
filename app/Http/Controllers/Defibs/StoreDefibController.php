<?php

declare(strict_types=1);

namespace App\Http\Controllers\Defibs;

use App\Models\Defib;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use App\Http\Requests\Defibs\UpsertDefibRequest;

class StoreDefibController extends Controller
{
    public function __invoke(UpsertDefibRequest $request): RedirectResponse
    {
        $defib = new Defib($request->validated());
        $defib->user_id = auth()->id();
        $defib->save();

        return redirect()->route('defibs.list')->with('success', 'Defib successfully added');
    }
}
