<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Firebase;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            $user = Auth::user();
            $token = $user->createToken('MyAppToken');
            $output = [
                'token' => $token->accessToken,
                'user' => $user,
                'token_life' => $token->token->expires_at
            ];

            return response()->json($output, 200);
        }

        return response()->json(['message' => 'Unauthorized'], 401);
    }

    public function logout() {
        auth()->logout();
        return response()->json(['message' => 'Successfully logged out']);
    }

    public function me() {
        return response()->json(auth()->user());
    }

    public function refresh() {
        $token = auth()->refresh();
        return response()->json(['token' => $token]);
    }

    public function payload() {
        return response()->json(auth()->payload());
    }

    public function register(Request $request) {
        try {
            $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|email|unique:users,email',
                'password' => 'required|string|min:6'
            ]);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 409);
        }

        try {
            User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => bcrypt($request->password)
            ]);
            return response()->json(['message' => 'Successfully registered']);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 409);
        }
    }

    public function registerFirebaseToken(Request $request): \Illuminate\Http\JsonResponse
    {
        $user = auth()->user();
        $user->remember_token = $request->token;
        $user->save();
        return response()->json(['message' => 'Successfully registered firebase token']);
    }

    public function newFirebaseToken(Request $request): \Illuminate\Http\JsonResponse
    {
        $token = $request->token;
        Firebase::create([
            'token' => $token
        ]);
        return response()->json(['message' => 'Successfully registered firebase token']);
    }

}
