<?php

declare(strict_types=1);

namespace App\Http\Controllers\Defibs\Inspections;

use App\Http\Controllers\Controller;
use App\Http\Requests\Defibs\Inspections\StoreDefibInspectionRequest;
use App\Models\Defib;
use App\Models\DefibInspection;
use App\Models\Member;
use Illuminate\Http\RedirectResponse;

class StoreDefibInspectionController extends Controller
{
    public function __invoke(StoreDefibInspectionRequest $request, int $id): RedirectResponse
    {
        $defib = Defib::find($id);
        $member = Member::query()->where('id', '=', $request->get('member_id'))->first();

        if (!$defib) {
            abort(404);
        }

        if (is_null(auth()->user())) {
            abort(401);
        }

        if (!$member) {
            abort(422);
        }

        $inspection = new DefibInspection($request->validated());
        $inspection->user_id = auth()->user()->id;
        $inspection->defib_id = $defib->id;
        $inspection->save();

        $defib->pads_expire_at = $request->get('pads_expire_at');
        $defib->battery_expires_at = $request->get('battery_expires_at');
        $defib->last_inspected_at = $request->get('inspected_at');
        $defib->last_inspected_by = $member->name;
        $defib->save();

        return redirect()->route('defibs.view', ['id' => $defib->id])->with('success', 'Defib inspection successfully added');
    }
}
