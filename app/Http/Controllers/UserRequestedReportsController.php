<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\Users\StoreRequestedReportsRequest;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;

class UserRequestedReportsController extends Controller
{
    public function show(User $user): View
    {
        $user->with('reports');

        if (! $user->reports) {
            $user->reports()->create();
            $user->refresh();
        }

        return view('users.reports', ['user' => $user]);
    }

    public function store(StoreRequestedReportsRequest $request, User $user): RedirectResponse
    {
        if (! $user->reports) {
            abort(500);
        }

        $user->reports->update([
            'cfr_cert_expiry' => $request->get('cfr_cert_expiry') === 'Yes',
            'defib_battery_expiry' => $request->get('defib_battery_expiry') === 'Yes',
            'defib_inspection' => $request->get('defib_inspection') === 'Yes',
            'defib_pad_expiry' => $request->get('defib_pad_expiry') === 'Yes',
            'garda_vetting_expiry' => $request->get('garda_vetting_expiry') === 'Yes',
            'defib_inspected' => $request->get('defib_inspected') === 'Yes',
        ]);

        return redirect()->route('users.show', ['user' => $user])->success('Requested Reports Updated Successfully');
    }
}
