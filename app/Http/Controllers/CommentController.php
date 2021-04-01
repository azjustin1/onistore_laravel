<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Tymon\JWTAuth\Facades\JWTAuth;

class CommentController extends Controller
{

    public function getAuthenticatedUser()
    {
        try {
            if (! $user = JWTAuth::parseToken()->authenticate()) {
                return response()->json(['user_not_found'], 404);
            }
        } catch (Tymon\JWTAuth\Exceptions\TokenExpiredException $e) {
            return response()->json(['token_expired'], $e->getStatusCode());
        } catch (Tymon\JWTAuth\Exceptions\TokenInvalidException $e) {
            return response()->json(['token_invalid'], $e->getStatusCode());
        } catch (Tymon\JWTAuth\Exceptions\JWTException $e) {
            return response()->json(['token_absent'], $e->getStatusCode());
        }

        return $user->id;
    }

    public function store(Request $request)
    {
        $request->validate([
            'text'=>'required',
        ]);

        $requestData = $request->all();

        $userId = $this->getAuthenticatedUser();

        $product = Product::with("image")->where("id", $requestData["id"])->first();

        $comment = new Comment();

        $comment->user_id = $userId;
        $comment->product_id = $product->id;
        $comment->text = $requestData['text'];

        if ($comment->save()) {
            return response()->json($comment);
        } else {
            return response()->json(["message" => "Store faild"], Response::HTTP_BAD_GATEWAY);
        }
    }
}
