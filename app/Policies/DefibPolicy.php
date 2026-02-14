<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\Defib;
use App\Models\User;

class DefibPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->can('view_any_defib');
    }

    public function view(User $user, Defib $defib): bool
    {
        return $user->can('view_defib');
    }

    public function create(User $user): bool
    {
        return $user->can('create_defib');
    }

    public function update(User $user, Defib $defib): bool
    {
        return $user->can('update_defib');
    }

    public function delete(User $user, Defib $defib): bool
    {
        return $user->can('delete_defib');
    }

    public function restore(User $user, Defib $defib): bool
    {
        return $user->can('restore_defib');
    }
}
