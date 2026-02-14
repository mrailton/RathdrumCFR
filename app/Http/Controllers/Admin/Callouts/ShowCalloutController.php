<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin\Callouts;

use App\Http\Controllers\Controller;
use App\Models\Callout;
use Illuminate\Contracts\View\View;

final class ShowCalloutController extends Controller
{
    public function __invoke(Callout $callout): View
    {
        $this->authorize('view', $callout);
        $callout->load('members');

        return view('admin.callouts.show', compact('callout'));
    }
}
