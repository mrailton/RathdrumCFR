<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin\TrainingSessions;

use App\Http\Controllers\Controller;
use App\Models\TrainingSession;
use Illuminate\Contracts\View\View;

final class ShowTrainingSessionController extends Controller
{
    public function __invoke(TrainingSession $trainingSession): View
    {
        $this->authorize('view', $trainingSession);
        $trainingSession->load('members');

        return view('admin.training-sessions.show', compact('trainingSession'));
    }
}
