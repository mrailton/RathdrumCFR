<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\Defibs\UpsertDefibRequest;
use App\Models\Defib;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;

class DefibController extends Controller
{
    public function list(): View
    {
        return view('defibs.list', [
            'defibs' => Defib::paginate(10),
        ]);
    }

    public function create(): View
    {
        return view('defibs.create');
    }

    public function store(UpsertDefibRequest $request): RedirectResponse
    {
        $defib = new Defib($request->validated());
        $defib->user_id = auth()->user()->id;
        $defib->save();

        toast()->success('Defib successfully added')->pushOnNextPage();

        return redirect()->route('defibs.list');
    }

    public function show(Defib $defib): View
    {
        $defib->load(['notes.author', 'inspections.member']);

        return view('defibs.view', ['defib' => $defib]);
    }

    public function edit(Defib $defib): View
    {
        return view('defibs.update', ['defib' => $defib]);
    }

    public function update(UpsertDefibRequest $request, Defib $defib): RedirectResponse
    {
        $defib->update($request->validated());

        toast()->success('Defib successfully updated');

        return redirect()->route('defibs.view', ['defib' => $defib->id]);
    }
}
