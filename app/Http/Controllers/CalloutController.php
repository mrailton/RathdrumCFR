<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\Callouts\StoreCalloutRequest;
use App\Models\Callout;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;

class CalloutController extends Controller
{
    public function create(): View
    {
        return view('callouts.create');
    }

    public function store(StoreCalloutRequest $request): RedirectResponse
    {
        $callout = new Callout($request->validated());
        $callout->user_id = auth()->id();
        $callout->save();

        toast()->success('Callout successfully logged')->pushOnNextPage();

        return redirect()->route('callouts.list');
    }

    public function show(Callout $callout): View
    {
        return view('callouts.show', ['callout' => $callout]);
    }
}
