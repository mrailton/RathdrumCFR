<?php

declare(strict_types=1);

namespace App\Http\Controllers\Users;

use App\Http\Controllers\Controller;
use App\Http\Requests\Users\StoreRequestedReportsRequest;
use App\Models\User;
use Illuminate\Http\RedirectResponse;

class StoreRequestedReportsController extends Controller
{
    public function __invoke(StoreRequestedReportsRequest $request, User $user): RedirectResponse
    {
        if (!$user->reports) {
            abort(500);
        }

        $user->reports->update([
            'cfr_cert_expiry' => $request->get('cfr_cert_expiry') === 'Yes',
            'defib_battery_expiry' => $request->get('defib_battery_expiry') === 'Yes',
            'defib_inspection' => $request->get('defib_inspection') === 'Yes',
            'defib_pad_expiry' => $request->get('defib_pad_expiry') === 'Yes',
            'garda_vetting_expiry' => $request->get('garda_vetting_expiry') === 'Yes',
        ]);

        return redirect()->route('users.show', ['user' => $user])->with('success', 'Requested Reports Updated Successfully');
    }
}
