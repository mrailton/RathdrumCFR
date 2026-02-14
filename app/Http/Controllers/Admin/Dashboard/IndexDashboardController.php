<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Callout;
use App\Models\Defib;
use App\Models\Member;
use Illuminate\Contracts\View\View;

final class IndexDashboardController extends Controller
{
    public function __invoke(): View
    {
        $memberStats = [
            'total' => Member::count(),
            'active' => Member::where('status', 'active')->count(),
            'inactive' => Member::where('status', 'inactive')->count(),
        ];

        $calloutStats = [
            'total' => Callout::whereYear('incident_date', now()->year)->count(),
            'attended' => Callout::whereYear('incident_date', now()->year)
                ->where('attended', true)
                ->count(),
            'ohca' => Callout::whereYear('incident_date', now()->year)
                ->where('ohca_at_scene', true)
                ->count(),
        ];

        $defibStats = [
            'total' => Defib::count(),
            'needs_attention' => Defib::where(function ($query): void {
                $query->where('battery_expires_at', '<', now()->addMonths(3))
                    ->orWhere('pads_expire_at', '<', now()->addMonths(3));
            })->count(),
        ];

        $recentCallouts = Callout::with('members')
            ->orderBy('incident_date', 'desc')
            ->limit(10)
            ->get();

        return view('admin.dashboard.index', compact(
            'memberStats',
            'calloutStats',
            'defibStats',
            'recentCallouts'
        ));
    }
}
