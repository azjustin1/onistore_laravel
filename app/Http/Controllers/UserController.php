<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use JWTAuth;

class UserController extends Controller
{
    /**
     * @var bool
     */

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */

    public function signup(Request $request)
    {
        $validator = Validator::make($request->all(), [
            "name" => ["required", "string", "max:255"],
            "email" => [
                "required",
                "string",
                "email",
                "max:255",
                "unique:users",
            ],
            "password" => ["required", "string", "min:8"],
        ]);
        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        } else {
            $user = User::create([
                "name" => $request["name"],
                "email" => $request["email"],
                "password" => Hash::make($request["password"]),
            ]);

            return response()->json(
                ["message" => "Signup successfully", "user" => $user],
                200
            );
        }
    }

    public function signin(Request $request)
    {
        $input = $request->only("email", "password");
        $token = null;

        if (!($token = JWTAuth::attempt($input))) {
            return response()->json(
                [
                    "status" => false,
                    "message" => "Invalid Email or Password",
                ],
                401
            );
        }

        return response()->json([
            "status" => true,
            "token" => $token,
        ]);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function logout(Request $request)
    {
        $this->validate($request, [
            "token" => "required",
        ]);

        try {
            JWTAuth::invalidate($request->token);

            return response()->json([
                "status" => true,
                "message" => "User logged out successfully",
            ]);
        } catch (JWTException $exception) {
            return response()->json(
                [
                    "status" => false,
                    "message" => "Sorry, the user cannot be logged out",
                ],
                500
            );
        }
    }
}