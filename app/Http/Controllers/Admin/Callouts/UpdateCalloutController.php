<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin\Callouts;

use App\Http\Controllers\Controller;
use App\Models\Callout;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

final class UpdateCalloutController extends Controller
{
    public function __invoke(Request $request, Callout $callout): RedirectResponse
    {
        $this->authorize('update', $callout);
        $validated = $request->validate([
            'incident_number' => ['required', 'string', 'max:255'],
            'incident_date' => ['required', 'date'],
            'ampds_code' => ['required', 'string', 'max:255'],
            'age' => ['required', 'integer', 'min:0'],
            'gender' => ['required', 'string', 'in:Unknown,Male,Female'],
            'mobilised' => ['required', 'boolean'],
            'medical_facility' => ['nullable', 'boolean'],
            'attended' => ['nullable', 'boolean'],
            'ohca_at_scene' => ['nullable', 'boolean'],
            'bystander_cpr' => ['nullable', 'boolean'],
            'source_of_aed' => ['nullable', 'string', 'in:CFR,PAD,NAS,Fire,Garda,Other'],
            'number_of_shocks_given' => ['nullable', 'integer', 'min:0'],
            'rosc_achieved' => ['nullable', 'boolean'],
            'patient_transported' => ['nullable', 'boolean'],
            'notes' => ['nullable', 'string'],
            'members' => ['nullable', 'array'],
            'members.*' => ['exists:members,id'],
        ]);

        $members = $validated['members'] ?? [];
        unset($validated['members']);

        $callout->update($validated);
        $callout->members()->sync($members);

        return redirect()
            ->route('admin.callouts.show', $callout)
            ->with('success', 'Callout updated successfully.');
    }
}
