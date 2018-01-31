<?php

namespace App\Policies;

use App\Permission;
use App\User;
use App\ToDo;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class ToDoPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view the ToDo.
     *
     * @param  \App\User  $user
     * @param  \App\ToDo  $toDo
     * @return mixed
     */
    public function view(User $user, ToDo $toDo)
    {
        if($user->id == $toDo->user_id) {
            return true;
        } else {
            try {
                Permission::where([
                    ['owner_id', $toDo->user_id],
                    ['receiver_id', $user->id]
                ])->firstOrFail();
            } catch (ModelNotFoundException $e) {
                return false;
            }
            return true;
        }
    }

    /**
     * Determine whether the user can create ToDos in the owner's list
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
     * Determine whether the user can update the ToDo.
     *
     * @param  \App\User  $user
     * @param  \App\ToDo  $toDo
     * @return mixed
     */
    public function update(User $user, ToDo $toDo)
    {
        //
    }

    /**
     * Determine whether the user can delete the ToDo.
     *
     * @param  \App\User  $user
     * @param  \App\ToDo  $toDo
     * @return mixed
     */
    public function delete(User $user, ToDo $toDo)
    {
        //
    }
}
