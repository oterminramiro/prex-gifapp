<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserLoginRequest;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function login(UserLoginRequest $request)
    {
        $request = $request->validated();
        
        $user = User::where('email', $request['email'])->first();
        if (!$user) {
            return response()->json([
                'status' => false,
                'data' => 'User does not exists',
            ], 400);
        }

        if (!Hash::check($request['password'], $user->password)) {
            return response()->json([
                'status' => false,
                'data' => 'Invalid password',
            ], 400);
        }

        $token = $user->createToken('auth_token');

        return response()->json([
            'status' => true,
            'data' => [
                'token' => $token->plainTextToken,
            ],
        ], 200);
    }
}
