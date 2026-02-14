<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\TrainingSession;
use App\Models\User;

class TrainingSessionPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->can('view_any_training_session');
    }

    public function view(User $user, TrainingSession $trainingSession): bool
    {
        return $user->can('view_training_session');
    }

    public function create(User $user): bool
    {
        return $user->can('create_training_session');
    }

    public function update(User $user, TrainingSession $trainingSession): bool
    {
        return $user->can('update_training_session');
    }

    public function delete(User $user, TrainingSession $trainingSession): bool
    {
        return $user->can('delete_training_session');
    }
}
