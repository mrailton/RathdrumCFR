<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin\TrainingSessions;

use App\Http\Controllers\Controller;
use App\Models\TrainingSession;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

final class IndexTrainingSessionsController extends Controller
{
    public function __invoke(Request $request): View
    {
        $this->authorize('viewAny', TrainingSession::class);
        $query = TrainingSession::query();

        // Date range filtering
        if ($from = $request->get('from')) {
            $query->whereDate('date', '>=', $from);
        }

        if ($to = $request->get('to')) {
            $query->whereDate('date', '<=', $to);
        }

        $trainingSessions = $query->orderBy('date', 'desc')->paginate(15)->withQueryString();

        return view('admin.training-sessions.index', compact('trainingSessions'));
    }
}
