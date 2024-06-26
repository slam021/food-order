<?php

namespace App\Http\Controllers\API;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function store(Request $request){
        $validation = $request->validate([
            'name' => 'required|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|max:255',
            'role_id' => 'required|'.Rule::in(['1','2','3','4']),
        ]);

        $validation['password'] = Hash::make($request->password);
        $user = User::create($validation);
        // dd($user);
        return response([
            'success' => true,
            'message' => 'User Berhasil disimpan!',
            'data' => $user,
        ]);
    }
}
