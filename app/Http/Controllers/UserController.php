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
        return response()->json($response, 201);
    }



    public function login(Request $request)
    {
        $validated = json_decode($request->getContent());

        $user = User::where("email", $validated->email)->first();
        if (is_null($user) || !Hash::check($validated->password, $user->password))
            return response()->json(["error" => "User credentials not found"], 401);
        $token = $user->createToken('shoof_token')->plainTextToken;
        $response = [
            'token' => $token,
            'user' => $user
        ];
        return response()->json($response, 200);
    }


    public function logout(Request $request)
    {
        $request->user()->tokens()->delete();

        $response = [
            "message" => "you have been logged out"
        ];
        return response()->json($response, 200);
    }
}
