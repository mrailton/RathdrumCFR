<?php

declare(strict_types=1);

namespace App\Http\Controllers\Callouts;

use App\Http\Controllers\Controller;
use App\Http\Requests\Callouts\StoreCalloutRequest;
use App\Models\Callout;
use Illuminate\Http\RedirectResponse;

class StoreCalloutController extends Controller
{
    public function __invoke(StoreCalloutRequest $request): RedirectResponse
    {
        $callout = new Callout($request->validated());
        $callout->user_id = auth()->id();
        $callout->save();

        return redirect()->route('callouts.list')->with('success', 'Callout successfully logged');
    }
}
