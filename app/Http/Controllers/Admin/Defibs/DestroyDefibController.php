<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin\Defibs;

use App\Http\Controllers\Controller;
use App\Models\Defib;
use Illuminate\Http\RedirectResponse;

final class DestroyDefibController extends Controller
{
    public function __invoke(Defib $defib): RedirectResponse
    {
        $this->authorize('delete', $defib);
        $defib->delete();

        return redirect()
            ->route('admin.defibs.index')
            ->with('success', 'Defib deleted successfully.');
    }
}
