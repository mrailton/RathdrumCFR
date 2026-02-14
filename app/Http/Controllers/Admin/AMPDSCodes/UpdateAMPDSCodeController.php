<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin\AMPDSCodes;

use App\Http\Controllers\Controller;
use App\Models\AMPDSCode;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

final class UpdateAMPDSCodeController extends Controller
{
    public function __invoke(Request $request, AMPDSCode $ampdsCode): RedirectResponse
    {
        $this->authorize('update', $ampdsCode);
        $validated = $request->validate([
            'code' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string', 'max:255'],
            'arrest_code' => ['nullable', 'boolean'],
            'far_code' => ['nullable', 'boolean'],
        ]);

        $ampdsCode->update($validated);

        return redirect()
            ->route('admin.ampds-codes.show', $ampdsCode)
            ->with('success', 'AMPDS Code updated successfully.');
    }
}
