<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin\Members;

use App\Http\Controllers\Controller;
use App\Models\Member;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

final class UpdateMemberController extends Controller
{
    public function __invoke(Request $request, Member $member): RedirectResponse
    {
        $this->authorize('update', $member);
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'status' => ['required', 'string', 'in:inactive,active,new recruit,buddying'],
            'title' => ['required', 'string', 'max:255'],
            'phone' => ['nullable', 'string', 'max:255'],
            'email' => ['nullable', 'email', 'max:255'],
            'address_1' => ['nullable', 'string', 'max:255'],
            'address_2' => ['nullable', 'string', 'max:255'],
            'eircode' => ['nullable', 'string', 'max:255'],
            'cfr_certificate_number' => ['nullable', 'string', 'max:255'],
            'cfr_certificate_expiry' => ['nullable', 'date'],
            'volunteer_declaration' => ['nullable', 'date'],
            'garda_vetting_id' => ['nullable', 'string', 'max:255'],
            'garda_vetting_date' => ['nullable', 'date'],
            'cism_completed' => ['nullable', 'date'],
            'child_first_completed' => ['nullable', 'date'],
            'ppe_community_completed' => ['nullable', 'date'],
            'ppe_acute_completed' => ['nullable', 'date'],
            'hand_hygiene_completed' => ['nullable', 'date'],
            'hiqa_completed' => ['nullable', 'date'],
        ]);

        $member->update($validated);

        return redirect()
            ->route('admin.members.show', $member)
            ->with('success', 'Member updated successfully.');
    }
}
