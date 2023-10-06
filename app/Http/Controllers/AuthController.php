<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User;


class AuthController extends Controller
{
    public function auth(Request $request)
    {
        $credentials = $request->only([
            'email', 'password', 'device_name'
        ]);
        
        $user = User::where('email', $request->email)->first();
        Hash::check($request->password, $user->password);
        if(!$user || !Hash::check($request->password, $user->password)){
            throw ValidationException::withMessages([
                'email' => ['email incorreto']
            ]);            
        }

        $user->tokens()->delete();

        $token = $user->createToken($request->device_name)->plainTextToken;

        return  response()->json([
            'token' => $token,
        ]);
    }

}
