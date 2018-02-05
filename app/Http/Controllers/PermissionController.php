<?php

namespace App\Http\Controllers;

use App\Permission;
use App\User;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PermissionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = Auth::user();
        $shared = $user->shared()->with('receiver')->get();
        $available = $user->available()->with('owner')->get();
        return response()->json(['shared' => $shared, 'available' => $available]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'receiver_name' => 'required',
            'can_write' => 'required|boolean'
        ]);
        $user = Auth::user();
        if($user->name === $data['receiver_name']){
            return response('Trying to give permission yourself', 400);
        }
        $receiver = User::where('name', $data['receiver_name'])->firstOrFail();
        try{
            $permission = $user->shared()->create(['receiver_id' => $receiver->id, 'can_write' => $data['can_write']]);
            return response()->json($permission);
        } catch (QueryException $exception){
            return response('Permission already exists', 400);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Permission $permission
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Permission $permission)
    {
        $data = $request->validate([
            'can_write' => 'required|boolean'
        ]);
        $permission->can_write = $data['can_write'];
        $permission->save();
        return response()->json($permission);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Permission $permission
     * @return \Illuminate\Http\Response
     */
    public function destroy(Permission $permission)
    {
        $permission->delete();
        return response('success');
    }
}
