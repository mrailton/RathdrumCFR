<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin\Members;

use App\Http\Controllers\Controller;
use App\Models\Member;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

final class IndexMembersController extends Controller
{
    public function __invoke(Request $request): View
    {
        $this->authorize('viewAny', Member::class);
        $query = Member::query()->withTrashed();

        // Search
        if ($search = $request->get('search')) {
            $query->where(function ($q) use ($search): void {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        // Status filter
        if ($status = $request->get('status')) {
            $query->where('status', $status);
        }

        // Trashed filter
        if ('only' === $request->get('trashed')) {
            $query->onlyTrashed();
        } elseif ('with' !== $request->get('trashed')) {
            $query->withoutTrashed();
        }

        $members = $query->orderBy('name')->paginate(15)->withQueryString();

        return view('admin.members.index', compact('members'));
    }
}
