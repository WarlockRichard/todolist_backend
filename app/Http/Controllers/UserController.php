<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Contracts\Hashing\Hasher;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Exceptions;

class UserController extends Controller
{

    public function index(Request $request){
        try{
            $user = auth()->userOrFail();
        } catch (Exceptions\TokenExpiredException $e) {

            return response()->json(['error' => 'token_expired'], 401);

        } catch (Exceptions\TokenInvalidException $e) {

            return response()->json(['error' => 'token_invalid'], 401);

        } catch (Exceptions\JWTException $e) {

            return response()->json(['error' => 'token_absent'], 401);

        }
        return ['user' => $user];
    }

    public function store(Request $request, Hasher $hasher){

        $credentials = $request->validate([
            'name' => 'required',
            'email' => 'required|email',
            'password' => 'required'
        ]);

        try{
            $user = User::create([
                'name' => $credentials['name'],
                'email' => $credentials['email'],
                'password' => $hasher->make($credentials['password'])
            ]);

            $token = auth()->fromUser($user);

            return ['token' => $token, 'user' => $user];
        } catch (QueryException $exception){
            $errorText = 'Error';
            if ($namesake = User::where('name', $credentials['name'])->first()) {
                $errorText .= "\nUser with this name already registered";
            }
            if ($namesake = User::where('email', $credentials['email'])->first()) {
                $errorText .= "\nUser with this email already registered";
            }
            return response($errorText, 401);
        }
    }


}
