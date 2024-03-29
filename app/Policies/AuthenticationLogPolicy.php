<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Rappasoft\LaravelAuthenticationLog\Models\AuthenticationLog;

class AuthenticationLogPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function viewAny(User $user): bool
    {
        return $user->can('view_any_authentication::log');
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \Rappasoft\LaravelAuthenticationLog\Models\AuthenticationLog  $authenticationLog
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(User $user, AuthenticationLog $authenticationLog): bool
    {
        return $user->can('view_authentication::log');
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function create(User $user): bool
    {
        return $user->can('create_authentication::log');
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \Rappasoft\LaravelAuthenticationLog\Models\AuthenticationLog  $authenticationLog
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $user, AuthenticationLog $authenticationLog): bool
    {
        return $user->can('update_authentication::log');
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \Rappasoft\LaravelAuthenticationLog\Models\AuthenticationLog  $authenticationLog
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user, AuthenticationLog $authenticationLog): bool
    {
        return $user->can('delete_authentication::log');
    }

    /**
     * Determine whether the user can bulk delete.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function deleteAny(User $user): bool
    {
        return $user->can('delete_any_authentication::log');
    }

    /**
     * Determine whether the user can permanently delete.
     *
     * @param  \App\Models\User  $user
     * @param  \Rappasoft\LaravelAuthenticationLog\Models\AuthenticationLog  $authenticationLog
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function forceDelete(User $user, AuthenticationLog $authenticationLog): bool
    {
        return $user->can('force_delete_authentication::log');
    }

    /**
     * Determine whether the user can permanently bulk delete.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function forceDeleteAny(User $user): bool
    {
        return $user->can('force_delete_any_authentication::log');
    }

    /**
     * Determine whether the user can restore.
     *
     * @param  \App\Models\User  $user
     * @param  \Rappasoft\LaravelAuthenticationLog\Models\AuthenticationLog  $authenticationLog
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function restore(User $user, AuthenticationLog $authenticationLog): bool
    {
        return $user->can('restore_authentication::log');
    }

    /**
     * Determine whether the user can bulk restore.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function restoreAny(User $user): bool
    {
        return $user->can('restore_any_authentication::log');
    }

    /**
     * Determine whether the user can replicate.
     *
     * @param  \App\Models\User  $user
     * @param  \Rappasoft\LaravelAuthenticationLog\Models\AuthenticationLog  $authenticationLog
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function replicate(User $user, AuthenticationLog $authenticationLog): bool
    {
        return $user->can('replicate_authentication::log');
    }

    /**
     * Determine whether the user can reorder.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function reorder(User $user): bool
    {
        return $user->can('reorder_authentication::log');
    }

}
