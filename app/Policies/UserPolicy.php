<?php

namespace App\Policies;

use App\Models\User;
use App\Models\UserApplication;
use Illuminate\Auth\Access\Response;

class UserPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function app_admin(User $user): bool {
        $app_admin = !is_null(UserApplication::select('id')
            ->where('user_id',$user->id)
            ->where('admin',true)
            ->first());
        return ($app_admin || $user->admin);
    }

    public function super_admin(User $user): bool {
        return $user->admin;
    }

}
