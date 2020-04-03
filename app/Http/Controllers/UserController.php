<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use JWTAuth;
use Auth;
use Tymon\JWTAuth\Exceptions\JWTException;

class UserController extends Controller
{
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:100',
            'email' => 'required|email|max:255|unique:users',
            'password' => 'required|string|min:8|max:255|confirmed',
            'password_confirmation' => 'required|string|min:8|max:255',
        ]);

        if ($validator->fails()) {
            return $this->respondInvalidParams($validator->messages());
        }

        $user = new User;
        $user->fill($request->all());
        $user->password = bcrypt($request->password);
        $user->save();

        return $this->respondWithUserData($user);
    }

    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        $validator = Validator::make($request->all(), [
            'email' => 'required|email|max:255',
            'password' => 'required|string|min:8|max:255',
        ]);

        if ($validator->fails()) {
            $this->respondInvalidParams($validator->message());
        }

        if (!$token = Auth::guard('api')->attempt(['email' => $credentials['email'], 'password' => $credentials['password']])) {
            return $this->respondUnauthorized();
        }

        return $this->respondWithToken($token);
    }

    public function logout()
    {
        Auth::guard('api')->logout();
        return $this->respondSuccess('logged out');
    }

    public function user()
    {
        $user = Auth::guard('api')->user();
        return $this->respondWithUserData($user);
    }

    public function refresh()
    {
        $token = Auth::guard('api')->refresh();
        return $this->respondWithToken($token);
    }

    public function unauthorized()
    {
        return $this->respondUnauthorized();
    }

    protected function respondSuccess($message)
    {
        return response()->json([
            'status' => 'success',
            'messages' => $message
        ], 200);
    }

    protected function respondInvalidParams($message)
    {
        return response()->json([
            'status' => 'error',
            'messages' => $message
        ], 400);
    }

    protected function respondUnauthorized()
    {
        return response()->json([
            'status' => 'error',
            'messages' => "Unauthorized"
        ], 401);
    }

    protected function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => Auth::guard('api')->factory()->getTTL() * 60
        ]);
    }

    protected function respondWithUserData($user)
    {
        return response()->json(compact('user'), 200);
    }
}
