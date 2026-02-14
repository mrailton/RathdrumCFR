<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin\Defibs;

use App\Http\Controllers\Controller;
use App\Models\Defib;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

final class StoreDefibController extends Controller
{
    public function __invoke(Request $request): RedirectResponse
    {
        $this->authorize('create', Defib::class);
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'location' => ['required', 'string', 'max:255'],
            'coordinates' => ['nullable', 'string', 'max:255'],
            'display_on_map' => ['boolean'],
            'model' => ['required', 'string', 'max:255'],
            'serial' => ['nullable', 'string', 'max:255'],
            'defib_lot_number' => ['nullable', 'string', 'max:255'],
            'defib_manufacture_date' => ['nullable', 'date'],
            'owner' => ['required', 'string', 'max:255'],
            'last_serviced_at' => ['nullable', 'date'],
            'last_inspected_at' => ['nullable', 'date'],
            'last_inspected_by' => ['nullable', 'string', 'max:255'],
            'battery_lot_number' => ['nullable', 'string', 'max:255'],
            'battery_manufacture_date' => ['nullable', 'date'],
            'battery_expires_at' => ['nullable', 'date'],
            'pads_lot_number' => ['nullable', 'string', 'max:255'],
            'pads_manufacture_date' => ['nullable', 'date'],
            'pads_expire_at' => ['nullable', 'date'],
        ]);

        $validated['display_on_map'] = $request->has('display_on_map');

        $defib = Defib::create($validated);

        return redirect()
            ->route('admin.defibs.show', $defib)
            ->with('success', 'Defib created successfully.');
    }
}
