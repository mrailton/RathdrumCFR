<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\Callout;
use App\Models\User;

class CalloutPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->can('view_any_callout');
    }

    public function view(User $user, Callout $callout): bool
    {
        return $user->can('view_callout');
    }

    public function create(User $user): bool
    {
        return $user->can('create_callout');
    }

    public function update(User $user, Callout $callout): bool
    {
        return $user->can('update_callout');
    }

    public function delete(User $user, Callout $callout): bool
    {
        return $user->can('delete_callout');
    }
}
