<?php

namespace App\Http\Controllers;

use App\Todo;
use App\User;
use Illuminate\Http\Request;

class UserTodosController extends Controller
{
    /**
     * Display a listing of the given User's todos.
     *
     * @param User $user
     * @return \Illuminate\Http\Response
     */
    public function index(User $user)
    {
        $this->authorize('listTodo', $user);
        return response()->json($user->todos);
    }

    /**
     * Store a newly created Todo for User in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param User $user
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, User $user)
    {
        $this->authorize('writeTodo', $user);
        $data = $request->validate([
            'text' => 'required',
            'checked' => 'required|boolean'
        ]);
        $todo = $user->todos()->create($data);
        return response()->json($todo);
    }

    /**
     * Set all User's todo's checked field in storage.
     *
     * @param Request $request
     * @param User $user
     * @return \Illuminate\Http\Response
     */
    public function updateAll(Request $request, User $user)
    {
        $this->authorize('writeTodo', $user);
        $data = $request->validate([
            'checked' => 'required|boolean'
        ]);
        $user->todos()->update(['checked' => $data['checked']]);

        return response('success');
    }

    /**
     * Remove all User's completed todos from storage.
     *
     * @param User $user
     * @return \Illuminate\Http\Response
     */
    public function destroyCompleted(User $user)
    {
        $this->authorize('writeTodo', $user);
        $user->todos()->where('checked', 1)->delete();

        return response('success');
    }
}
