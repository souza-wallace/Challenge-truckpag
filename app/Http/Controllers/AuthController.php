<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User;


class AuthController extends Controller
{
    /**
     * @OA\Post(
     *     path="/api/v1/auth",
     *     tags={"auth"},
     *     summary="Use this endpoint to get a token",
     *     description="Use this endpoint to get a token, the token it will provide access to consume the api ",
     *     operationId="getToken",
     *     @OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent(
     *              type="object",
     *              @OA\Property(property="email", type="string", example="test@example.com"),
     *              @OA\Property(property="password", type="string", example="1234"),
     *              @OA\Property(property="device_name", type="string", example="Swagger Client")
     *          )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="successful operation",
     *     ),
     * )
     *
     * @param int $code
     */
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
