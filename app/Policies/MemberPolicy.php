<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\Member;
use App\Models\User;

class MemberPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->can('view_any_member');
    }

    public function view(User $user, Member $member): bool
    {
        return $user->can('view_member');
    }

    public function create(User $user): bool
    {
        return $user->can('create_member');
    }

    public function update(User $user, Member $member): bool
    {
        return $user->can('update_member');
    }

    public function delete(User $user, Member $member): bool
    {
        return $user->can('delete_member');
    }

    public function restore(User $user, Member $member): bool
    {
        return $user->can('restore_member');
    }
}
