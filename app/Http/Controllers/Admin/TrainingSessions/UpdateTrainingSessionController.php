<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin\TrainingSessions;

use App\Http\Controllers\Controller;
use App\Models\TrainingSession;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

final class UpdateTrainingSessionController extends Controller
{
    public function __invoke(Request $request, TrainingSession $trainingSession): RedirectResponse
    {
        $this->authorize('update', $trainingSession);
        $validated = $request->validate([
            'date' => ['required', 'date'],
            'topic' => ['required', 'string', 'max:255'],
            'notes' => ['nullable', 'string'],
            'members' => ['required', 'array', 'min:1'],
            'members.*' => ['exists:members,id'],
        ]);

        $trainingSession->update([
            'date' => $validated['date'],
            'topic' => $validated['topic'],
            'notes' => $validated['notes'] ?? null,
        ]);

        $trainingSession->members()->sync($validated['members']);

        return redirect()
            ->route('admin.training-sessions.show', $trainingSession)
            ->with('success', 'Training session updated successfully.');
    }
}
