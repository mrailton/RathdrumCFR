<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\Callouts\StoreCalloutRequest;
use App\Models\Callout;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class CalloutController extends Controller
{
    public function list(Request $request): View
    {
        $callouts = Callout::orderBy('incident_date')->paginate(10);

        return view('callouts.list', ['callouts' => $callouts]);
    }

    public function create(Request $request): View
    {
        return view('callouts.create');
    }

    public function store(StoreCalloutRequest $request): RedirectResponse
    {
        $callout = new Callout($request->validated());
        $callout->user_id = auth()->id();
        $callout->save();

        return redirect()->route('callouts.list')->with('success', 'Callout successfully logged');
    }

    public function show(Request $request, Callout $callout): View
    {
        return view('callouts.show', ['callout' => $callout]);
    }
}
