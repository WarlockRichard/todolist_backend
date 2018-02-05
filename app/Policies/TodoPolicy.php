<?php

namespace App\Policies;

use App\Permission;
use App\User;
use App\Todo;
use Illuminate\Auth\Access\HandlesAuthorization;

class TodoPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can modify Todo
     *
     * @param  \App\User  $user
     * @param  \App\Todo  $todo
     * @return mixed
     */
    public function modify(User $user, Todo $todo)
    {
        if($user->id === $todo->user_id) {
            return true;
        } else {
            $permission = Permission::where([
                ['owner_id', $todo->user_id],
                ['receiver_id', $user->id]
            ])->first();
            return $permission->can_write;
        }
    }
}
