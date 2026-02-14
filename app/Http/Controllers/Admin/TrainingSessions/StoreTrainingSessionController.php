<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin\TrainingSessions;

use App\Http\Controllers\Controller;
use App\Models\TrainingSession;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

final class StoreTrainingSessionController extends Controller
{
    public function __invoke(Request $request): RedirectResponse
    {
        $this->authorize('create', TrainingSession::class);
        $validated = $request->validate([
            'date' => ['required', 'date'],
            'topic' => ['required', 'string', 'max:255'],
            'notes' => ['nullable', 'string'],
            'members' => ['required', 'array', 'min:1'],
            'members.*' => ['exists:members,id'],
        ]);

        $trainingSession = TrainingSession::create([
            'date' => $validated['date'],
            'topic' => $validated['topic'],
            'notes' => $validated['notes'] ?? null,
        ]);

        $trainingSession->members()->attach($validated['members']);

        return redirect()
            ->route('admin.training-sessions.show', $trainingSession)
            ->with('success', 'Training session created successfully.');
    }
}
