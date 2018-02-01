<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Contracts\Hashing\Hasher;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Tymon\JWTAuth\Exceptions;

class AuthController extends Controller
{
    /**
     * Create a new AuthController instance.
     *
     */
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login', 'register']]);
    }

    public function register(Request $request, Hasher $hasher)
    {
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

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);
        try {
            // attempt to verify the credentials and create a token for the user

            if (! $token = auth()->attempt($credentials)) {
                return response()->json(['error' => 'Invalid credentials'], 401);
            }
        } catch (Exceptions\JWTException $e) {
            // something went wrong whilst attempting to encode the token
            return response()->json(['error' => 'could_not_create_token'], 500);
        }

        return ['token' => $token, 'user' => Auth::user()];
    }

    public function logout(Request $request)
    {
        try{
            auth()->invalidate();

        } catch (Exceptions\TokenExpiredException $e) {

            return response()->json(['error' => 'token_expired'], 401);

        } catch (Exceptions\TokenInvalidException $e) {

            return response()->json(['error' => 'token_invalid'], 401);

        } catch (Exceptions\JWTException $e) {

            return response()->json(['error' => 'token_absent'], 401);

        }
        return response('success', 200);
    }

    public function me(Request $request)
    {
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
}
