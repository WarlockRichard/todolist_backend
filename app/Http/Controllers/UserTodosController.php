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
     * @param mixed $user_id
     * @return \Illuminate\Http\Response
     */
    public function index($user_id)
    {
        $user = User::findOrFail($user_id);
        return response()->json($user->todos);
    }

    /**
     * Store a newly created Todo for User in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param $user_id
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, $user_id)
    {
        $user = User::findOrFail($user_id);

        $data = $request->validate([
            'text' => 'required',
            'checked' => 'required|boolean'
        ]);
        $todo = $user->todos()->create($data);
        return response()->json($todo);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param $user_id
     * @param $todo_id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $user_id, $todo_id)
    {
        $todo = Todo::findOrFail($todo_id);
        $data = $request->validate([
            'text' => 'required',
            'checked' => 'required|boolean'
        ]);
        $todo->text = $data['text'];
        $todo->checked = $data['checked'];

        $todo->save();
        return response()->json($todo);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param $todo_id
     * @return \Illuminate\Http\Response
     */
    public function destroy($user_id, $todo_id)
    {
        $todo = Todo::findOrFail($todo_id);
        $todo->delete();
        return response('success');
    }

    /**
     * Set all User's todo's checked field in storage.
     *
     * @param $user_id
     * @return \Illuminate\Http\Response
     */
    public function updateAll(Request $request, $user_id)
    {
        $data = $request->validate([
            'checked' => 'required|boolean'
        ]);
        $user = User::findOrFail($user_id);
        $user->todos()->update(['checked' => $data['checked']]);

        return response('success');
    }

    /**
     * Remove all User's completed todos from storage.
     *
     * @param $user_id
     * @return \Illuminate\Http\Response
     */
    public function destroyCompleted($user_id)
    {
        $user = User::findOrFail($user_id);
        $user->todos()->where('checked', 1)->delete();

        return response('success');
    }
}
