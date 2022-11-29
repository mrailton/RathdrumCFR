<?php

declare(strict_types=1);

namespace App\Http\Controllers\Defibs\Inspections;

use App\Models\Defib;
use App\Models\Member;
use App\Models\DefibInspection;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use App\Http\Requests\Defibs\Inspections\StoreDefibInspectionRequest;

class StoreDefibInspectionController extends Controller
{
    public function __invoke(StoreDefibInspectionRequest $request, int $id): RedirectResponse
    {
        $defib = Defib::find($id);
        $member = Member::find($request->get('member_id'));

        $inspection = new DefibInspection($request->validated());
        $inspection->user_id = auth()->user()->id;
        $inspection->defib_id = $defib->id;
        $inspection->save();

        $defib->pads_expire_at = $request->get('pads_expire_at');
        $defib->battery_expires_at = $request->get('battery_expires_at');
        $defib->last_inspected_at = $request->get('inspected_at');
        $defib->last_inspected_by = $member->name;
        $defib->save();

        return redirect()->route('defibs.view', ['id' => $defib->id]);
    }
}
