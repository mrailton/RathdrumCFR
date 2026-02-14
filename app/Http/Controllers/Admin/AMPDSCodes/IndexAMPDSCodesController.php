<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin\AMPDSCodes;

use App\Http\Controllers\Controller;
use App\Models\AMPDSCode;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

final class IndexAMPDSCodesController extends Controller
{
    public function __invoke(Request $request): View
    {
        $this->authorize('viewAny', AMPDSCode::class);
        $query = AMPDSCode::query();

        // Search
        if ($search = $request->get('search')) {
            $query->where(function ($q) use ($search): void {
                $q->where('code', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            });
        }

        $ampds_codes = $query->orderBy('code')->paginate(15)->withQueryString();

        return view('admin.ampds-codes.index', compact('ampds_codes'));
    }
}
