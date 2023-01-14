<?php

namespace KieranFYI\UserUI\Policies;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use KieranFYI\Roles\Core\Policies\AbstractPolicy;

class UserPolicy extends AbstractPolicy
{
    /**
     * Determine whether the user can update the model.
     *
     * @param mixed $user
     * @param Model $model
     * @return bool
     */
    public function update(mixed $user, Model $model): bool
    {
        return $this->hasPermission($user, 'Update');
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param mixed $user
     * @param Model $model
     * @return bool
     */
    public function delete(mixed $user, Model $model): bool
    {
        return $this->hasPermission($user, 'Delete') && Auth::check() && !$model->is($user);
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param mixed $user
     * @param Model $model
     * @return bool
     */
    public function restore(mixed $user, Model $model): bool
    {
        return $this->hasPermission($user, 'Restore');
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param mixed $user
     * @param Model $model
     * @return bool
     */
    public function forceDelete(mixed $user, Model $model): bool
    {
        return $this->hasPermission($user, 'Force Delete') && Auth::check() && !$model->is($user);
    }
}