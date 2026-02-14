<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\AMPDSCode;
use App\Models\User;

class AMPDSCodePolicy
{
    public function viewAny(User $user): bool
    {
        return $user->can('view_any_ampds_code');
    }

    public function view(User $user, AMPDSCode $aMPDSCode): bool
    {
        return $user->can('view_ampds_code');
    }

    public function create(User $user): bool
    {
        return $user->can('create_ampds_code');
    }

    public function update(User $user, AMPDSCode $aMPDSCode): bool
    {
        return $user->can('update_ampds_code');
    }

    public function delete(User $user, AMPDSCode $aMPDSCode): bool
    {
        return $user->can('delete_ampds_code');
    }
}
