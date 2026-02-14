<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin\Defibs;

use App\Http\Controllers\Controller;
use App\Models\Defib;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

final class IndexDefibsController extends Controller
{
    public function __invoke(Request $request): View
    {
        $this->authorize('viewAny', Defib::class);
        $query = Defib::query()->withTrashed();

        // Search
        if ($search = $request->get('search')) {
            $query->where(function ($q) use ($search): void {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('location', 'like', "%{$search}%")
                    ->orWhere('model', 'like', "%{$search}%")
                    ->orWhere('serial', 'like', "%{$search}%");
            });
        }

        // Trashed filter
        if ('only' === $request->get('trashed')) {
            $query->onlyTrashed();
        } elseif ('with' !== $request->get('trashed')) {
            $query->withoutTrashed();
        }

        $defibs = $query->orderBy('name')->paginate(15)->withQueryString();

        return view('admin.defibs.index', compact('defibs'));
    }
}
