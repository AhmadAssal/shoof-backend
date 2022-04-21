<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

use Illuminate\Support\Facades\Hash;

use App\Http\Requests\RegisterRequest;

class UserController extends Controller
{
    public function register(RegisterRequest $request)
    {
        $validated = $request->validated();
        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password'])
        ]);

        $token = $user->createToken('shoof_token')->plainTextToken;
        $response = [
            'token' => $token,
            'user' => $user
        ];
        return response($response, 201);
    }
}
