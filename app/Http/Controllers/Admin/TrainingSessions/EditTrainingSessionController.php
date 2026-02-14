<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin\TrainingSessions;

use App\Http\Controllers\Controller;
use App\Models\Member;
use App\Models\TrainingSession;
use Illuminate\Contracts\View\View;

final class EditTrainingSessionController extends Controller
{
    public function __invoke(TrainingSession $trainingSession): View
    {
        $this->authorize('update', $trainingSession);
        $trainingSession->load('members');
        $members = Member::query()->orderBy('name')->get();

        return view('admin.training-sessions.edit', compact('trainingSession', 'members'));
    }
}
