<?php

declare(strict_types=1);

namespace App\Http\Controllers\Defibs;

use App\Http\Controllers\Controller;
use App\Http\Requests\Defibs\UpsertDefibRequest;
use App\Models\Defib;
use Illuminate\Http\RedirectResponse;

class StoreDefibController extends Controller
{
    public function __invoke(UpsertDefibRequest $request): RedirectResponse
    {
        $defib = new Defib($request->validated());
        $defib->user_id = auth()->user()->id;
        $defib->save();

        return redirect()->route('defibs.list')->with('success', 'Defib successfully added');
    }
}
