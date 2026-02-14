<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin\Callouts;

use App\Http\Controllers\Controller;
use App\Models\Callout;
use Illuminate\Http\RedirectResponse;

final class DestroyCalloutController extends Controller
{
    public function __invoke(Callout $callout): RedirectResponse
    {
        $this->authorize('delete', $callout);
        $callout->delete();

        return redirect()
            ->route('admin.callouts.index')
            ->with('success', 'Callout deleted successfully.');
    }
}
