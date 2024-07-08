<?php

namespace App\Http\Controllers\API;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function login(Request $request){
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $user = User::where('email', $request->email)->first();

        if (! $user || ! Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['The provided credentials are incorrect.'],
            ]);
        }

        unset($user->email_verified_at);
        unset($user->created_at);
        unset($user->updated_at);
        unset($user->deleted_at);

        $user->tokens()->delete();

        $token = $user->createToken('login')->plainTextToken;
        $user->token = $token;

        return response([
            'success' => true,
            'message' => 'Login Berhasil!',
            'data' => $user,
        ]);
    }

    public function me(){
        $user = auth()->user();

        return response([
            'success' => true,
            'message' => 'Me!',
            'data' => $user,
        ]);
    }

    public function logout(){
        $user = auth()->user();

        $user->tokens()->delete();

        return response([
            'success' => true,
            'message' => 'Berhasil Logout!',
        ]);
    }
}
