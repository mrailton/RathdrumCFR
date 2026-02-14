<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin\TrainingSessions;

use App\Http\Controllers\Controller;
use App\Models\Member;
use App\Models\TrainingSession;
use Illuminate\Contracts\View\View;

final class CreateTrainingSessionController extends Controller
{
    public function __invoke(): View
    {
        $this->authorize('create', TrainingSession::class);
        $members = Member::query()->orderBy('name')->get();

        return view('admin.training-sessions.create', compact('members'));
    }
}
