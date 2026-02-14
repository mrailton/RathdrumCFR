<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin\Callouts;

use App\Http\Controllers\Controller;
use App\Models\Callout;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

final class IndexCalloutsController extends Controller
{
    public function __invoke(Request $request): View
    {
        $this->authorize('viewAny', Callout::class);
        $query = Callout::query();

        // Search
        if ($search = $request->get('search')) {
            $query->where(function ($q) use ($search): void {
                $q->where('incident_number', 'like', "%{$search}%")
                    ->orWhere('ampds_code', 'like', "%{$search}%");
            });
        }

        // Attended filter
        if ($request->has('attended') && '' !== $request->get('attended')) {
            $query->where('attended', (bool) $request->get('attended'));
        }

        // OHCA at scene filter
        if ($request->has('ohca_at_scene') && '' !== $request->get('ohca_at_scene')) {
            $query->where('ohca_at_scene', (bool) $request->get('ohca_at_scene'));
        }

        // Date range filter
        if ($from = $request->get('from')) {
            $query->whereDate('incident_date', '>=', $from);
        }
        if ($to = $request->get('to')) {
            $query->whereDate('incident_date', '<=', $to);
        }

        $callouts = $query->orderBy('incident_date', 'desc')->paginate(15)->withQueryString();

        return view('admin.callouts.index', compact('callouts'));
    }
}
