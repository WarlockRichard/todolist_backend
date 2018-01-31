<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\JWTAuth;

class AuthController extends Controller
{
    /**
     * Create a new AuthController instance.
     *
     * @return void
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
        } catch (JWTException $e) {
            // something went wrong whilst attempting to encode the token
            return response()->json(['error' => 'could_not_create_token'], 500);
        }

        return ['token' => $token, 'status' => 'success'];//response()->json()
    }

    public function logout(Request $request)
    {
        try{
            auth()->parseToken()->invalidate();

        } catch (Exceptions\TokenExpiredException $e) {

            return response()->json(['error' => 'token_expired'], 401);

        } catch (Exceptions\TokenInvalidException $e) {

            return response()->json(['error' => 'token_invalid'], 401);

        } catch (Exceptions\JWTException $e) {

            return response()->json(['error' => 'token_absent'], 401);

        }
//        auth()->invalidate();
        return ['status' => 'success'];
    }

    public function getUser(Request $request)
    {
        try{
            auth()->setToken($request->header('Authorization'));
            $user = auth()->toUser();
        } catch (Exceptions\TokenExpiredException $e) {

            return response()->json(['error' => 'token_expired'], 401);

        } catch (Exceptions\TokenInvalidException $e) {

            return response()->json(['error' => 'token_invalid'], 401);

        } catch (Exceptions\JWTException $e) {

            return response()->json(['error' => 'token_absent'], 401);

        }
        return ['status' => 'success', 'data' => $user];
    }
}
