<?php

namespace App\Policies;

use App\Permission;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserTodosPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can list owner's todos.
     *
     * @param  \App\User $user
     * @param \App\User $owner
     * @return mixed
     */
    public function listTodo(User $user, User $owner)
    {
        if($user === $owner) {
            return true;
        } else {
            return Permission::where([
                    ['owner_id', $owner->id],
                    ['receiver_id', $user->id]
                ])->first() != null;
        }
    }

    /**
     * Determine whether the user can write todos for the owner.
     *
     * @param  \App\User $user
     * @param \App\User $owner
     * @return mixed
     */
    public function writeTodo(User $user, User $owner)
    {
        if($user === $owner) {
            return true;
        } else {
            $permission = Permission::where([
                    ['owner_id', $owner->id],
                    ['receiver_id', $user->id]
                ])->first();
            return $permission->can_write;
        }
    }
}
