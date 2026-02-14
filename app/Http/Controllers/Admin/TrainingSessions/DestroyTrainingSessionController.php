<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin\TrainingSessions;

use App\Http\Controllers\Controller;
use App\Models\TrainingSession;
use Illuminate\Http\RedirectResponse;

final class DestroyTrainingSessionController extends Controller
{
    public function __invoke(TrainingSession $trainingSession): RedirectResponse
    {
        $this->authorize('delete', $trainingSession);
        $trainingSession->delete();

        return redirect()
            ->route('admin.training-sessions.index')
            ->with('success', 'Training session deleted successfully.');
    }
}
