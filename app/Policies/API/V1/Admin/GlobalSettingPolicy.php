<?php

namespace App\Policies\API\V1\Admin;

use App\Models\GlobalSetting;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class GlobalSettingPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return auth()->guard('api')->user()->user_type->getPrecedence() < 3;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, GlobalSetting $globalSetting): bool
    {
        return auth()->guard('api')->user()->user_type->getPrecedence() < 3;
    }

    /**
     * Determine whether the user can create models.
     */

    public function create(User $user): bool
    {
        return false;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, GlobalSetting $globalSetting): bool
    {
        return false;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, GlobalSetting $globalSetting): bool
    {
        return false;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, GlobalSetting $globalSetting): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, GlobalSetting $globalSetting): bool
    {
        return false;
    }
}
