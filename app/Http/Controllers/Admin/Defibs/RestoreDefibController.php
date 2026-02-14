<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin\Defibs;

use App\Http\Controllers\Controller;
use App\Models\Defib;
use Illuminate\Http\RedirectResponse;

final class RestoreDefibController extends Controller
{
    public function __invoke(int $defib): RedirectResponse
    {
        $defib = Defib::onlyTrashed()->findOrFail($defib);
        $this->authorize('restore', $defib);
        $defib->restore();

        return redirect()
            ->route('admin.defibs.index')
            ->with('success', 'Defib restored successfully.');
    }
}
