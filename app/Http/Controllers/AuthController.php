<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Contracts\Hashing\Hasher;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    /**
     * Create a new AuthController instance.
     *
     */
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login']]);
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
}
