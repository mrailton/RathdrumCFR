<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\Defibs\UpsertDefibRequest;
use App\Models\Defib;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class DefibController extends Controller
{
    public function list(Request $request): View
    {
        return view('defibs.list', [
            'defibs' => Defib::paginate(10),
        ]);
    }

    public function create(Request $request): View
    {
        return view('defibs.create');
    }

    public function store(UpsertDefibRequest $request): RedirectResponse
    {
        $defib = new Defib($request->validated());
        $defib->user_id = auth()->user()->id;
        $defib->save();

        return redirect()->route('defibs.list')->with('success', 'Defib successfully added');
    }

    public function show(Request $request, Defib $defib): View
    {
        $defib->load(['notes.author', 'inspections.member']);

        return view('defibs.view', ['defib' => $defib]);
    }

    public function edit(Request $request, Defib $defib): View
    {
        return view('defibs.update', ['defib' => $defib]);
    }

    public function update(UpsertDefibRequest $request, Defib $defib): RedirectResponse
    {
        $defib->update($request->validated());

        return redirect()->route('defibs.view', ['defib' => $defib->id])->with('success', 'Defib successfully updated');
    }
}
