<?php

namespace App\Http\Controllers;

use App\Models\AuthUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthUserController extends Controller
{

    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email|max:255|unique:auth_users',
            'password' => 'required|string|min:6|confirmed',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $user = AuthUser::create([
            'email' => $request->input('email'),
            'password' => Hash::make($request->input('password')),
        ]);

        return $this->createTokenResponseForUser($user);
    }

    public function login(Request $request)
    {
        $user = AuthUser::where('email', $request->input('email'))->firstOrFail();
        if (!Hash::check($request->input('password'), $user->password)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
        return $this->createTokenResponseForUser($user);
    }

    private function createTokenResponseForUser(AuthUser $user){
        $token = $user->createToken('auth_token')->plainTextToken;
        return response()->json([
            'token' => $token,
            'type' => 'Bearer'
        ], 200);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        return response()->json(['message' => 'Successfully logged out'], 200);
    }

    public function me(Request $request)
    {
        return response()->json($request->user());
    }

    public function reservations(Request $request)
    {
        return response()->json($request->user()->reservations);
    }
}
