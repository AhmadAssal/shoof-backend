<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

use Illuminate\Support\Facades\Hash;

use App\Http\Requests\RegisterRequest;
use App\Mail\ResetPasswordMail;
use Illuminate\Support\Facades\Password;
use Illuminate\Auth\Passwords\PasswordBroker;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class UserController extends Controller
{
    public function register(RegisterRequest $request)
    {
        $validated = json_decode($request->getContent());
        $user = User::create([
            'name' => $validated->name,
            'email' => $validated->email,
            'password' => Hash::make($validated->password)
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

        $user = User::where("email", $validated->email)->with('watchlists')->first();
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

    public function createForgetPasswordToken(Request $request)
    {
        $user = User::where('email', $request->email)->first();
        $token = app(PasswordBroker::class)->createToken($user);
        Mail::to($request->email)->send(new ResetPasswordMail($token));
        return response()->json(['message' => 'Token created.', 'token' => $token], 200);
    }

    public function resetPassword(Request $request)
    {
        $user = User::where('email', $request->email)->first();
        $isTokenValid = app(PasswordBroker::class)->tokenExists($user, $request->token);
        if (!$isTokenValid) {
            return response()->json(['error' => 'This token expired or is not valid.'], 401);
        } else {
            $user->password = Hash::make($request->newPassword);
            $user->save();
            $actualToken = DB::table('password_resets')->where('email', '=', $request->email)->delete();
            return response()->json(['message' => 'User updated successfully.'], 200);
        }
    }
}
