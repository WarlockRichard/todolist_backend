<?php

namespace App\Policies;

use App\Permission;
use App\User;
use App\Todo;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class TodoPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view the Todo.
     *
     * @param  \App\User  $user
     * @param  \App\Todo  $todo
     * @return mixed
     */
    public function view(User $user, Todo $todo)
    {
        if($user->id == $todo->user_id) {
            return true;
        } else {
            try {
                Permission::where([
                    ['owner_id', $todo->user_id],
                    ['receiver_id', $user->id]
                ])->firstOrFail();
            } catch (ModelNotFoundException $e) {
                return false;
            }
            return true;
        }
    }

    /**
     * Determine whether the user can create Todos in the owner's list
     *
     * @param  \App\User  $user
     * @param  \App\User  $owner
     * @return mixed
     */
    public function create(User $user, User $owner)
    {
        if($user === $owner) {
            return true;
        } else {
            try {
                $permission = Permission::where([
                    ['owner_id', $owner->id],
                    ['receiver_id', $user->id]
                ])->firstOrFail();
            } catch (ModelNotFoundException $e) {
                return false;
            }
            return $permission->can_write;
        }
    }

    /**
     * Determine whether the user can update the Todo.
     *
     * @param  \App\User  $user
     * @param  \App\Todo  $todo
     * @return mixed
     */
    public function update(User $user, Todo $todo)
    {
        //
    }

    /**
     * Determine whether the user can delete the Todo.
     *
     * @param  \App\User  $user
     * @param  \App\Todo  $todo
     * @return mixed
     */
    public function delete(User $user, Todo $todo)
    {
        //
    }
}
