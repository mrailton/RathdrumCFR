<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Enums\BatteryCondition;
use App\Events\DefibInspected;
use App\Http\Requests\Defibs\Inspections\StoreDefibInspectionRequest;
use App\Models\Defib;
use App\Models\DefibInspection;
use App\Models\Member;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class DefibInspectionController extends Controller
{
    public function create(Request $request, Defib $defib): View
    {
        return view('defibs.inspections.create', [
            'defib' => $defib,
            'members' => Member::all(),
            'batteryConditions' => BatteryCondition::toArray(),
        ]);
    }

    public function store(StoreDefibInspectionRequest $request, Defib $defib): RedirectResponse
    {
        $member = Member::query()->where('id', '=', $request->get('member_id'))->first();

        $inspection = new DefibInspection($request->validated());
        $inspection->user_id = auth()->user()->id;
        $inspection->defib_id = $defib->id;
        $inspection->save();

        $defib->pads_expire_at = $request->get('pads_expire_at');
        $defib->battery_expires_at = $request->get('battery_expires_at');
        $defib->last_inspected_at = $request->get('inspected_at');
        $defib->last_inspected_by = $member->name;
        $defib->save();

        DefibInspected::dispatch($defib, $inspection);

        return redirect()->route('defibs.view', ['defib' => $defib->id])->with('success', 'Defib inspection successfully added');
    }
}
