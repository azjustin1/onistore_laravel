<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
//use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Facades\JWTAuth;

class UserController extends Controller
{
    /**
     * @var bool
     */

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */

    public function index() {
        $user = User::all();
        return response()->json($user, Response::HTTP_OK)
            ->header('X-Total-Count', User::all()->count())
            ->header("Access-Control-Expose-Headers", "X-Total-Count");
    }

    public function adminShow($id): \Illuminate\Http\JsonResponse
    {
        $user = User::with("rating")->find($id);
        if (!isset($user)) {
            return response()->json(["message" => "Not found"], Response::HTTP_NOT_FOUND);
        } else {
            return response()->json($user, Response::HTTP_OK);
        }
    }

    public function adminEdit(Request $request, $id)
    {
        $validate = Validator::make($request->all(), [
            "username" => ["required", "string", "max:255"],
            "email" => [
                "required",
                "string",
                "email",
                "max:255",
                "unique:users",
            ],
            "role" => "required"
        ]);

        $user = User::with("rating")->find($id);

        if ($validate->fails()) {
            return response()->json($validate->errors(), Response::HTTP_BAD_REQUEST);
        } else {
            $requestData = $request->all();
            if ($user->update($requestData)) {
                return response()->json(["message" => "Update Successfully"], Response::HTTP_OK);
            } else {
                return response()->json(["message" => "Update failed"], Response::HTTP_BAD_REQUEST);
            }
        }
    }

    public function signup(Request $request)
    {
        $validator = Validator::make($request->all(), [
            "username" => ["required", "string", "max:255"],
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
                "username" => $request["username"],
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
        $input = $request->only("username", "password");
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
