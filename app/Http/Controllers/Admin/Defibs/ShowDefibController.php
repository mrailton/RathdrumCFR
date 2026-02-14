<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin\Defibs;

use App\Http\Controllers\Controller;
use App\Models\Defib;
use Illuminate\Contracts\View\View;

final class ShowDefibController extends Controller
{
    public function __invoke(Defib $defib): View
    {
        $this->authorize('view', $defib);
        $defib->load(['inspections', 'notes']);

        return view('admin.defibs.show', compact('defib'));
    }
}
