<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\Callouts\StoreCalloutRequest;
use App\Models\Callout;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;

class CalloutController extends Controller
{
    public function list(): View
    {
        $callouts = Callout::orderBy('incident_date')->paginate(10);

        return view('callouts.list', ['callouts' => $callouts]);
    }

    public function create(): View
    {
        return view('callouts.create');
    }

    public function store(StoreCalloutRequest $request): RedirectResponse
    {
        $callout = new Callout($request->validated());
        $callout->user_id = auth()->id();
        $callout->save();

        return redirect()->route('callouts.list')->success('Callout successfully logged');
    }

    public function show(Callout $callout): View
    {
        return view('callouts.show', ['callout' => $callout]);
    }
}
