<?php

namespace App\Http\Controllers;

use App\Http\Middleware\Ulti;
use App\Models\Comment;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;

class CommentController extends Controller
{

    public function index()
    {
        $product = Product::with("comment")->get();
        return response()->json($product, Response::HTTP_OK);
    }

    public function store(Request $request)
    {
        $validate = Validator::make($request->all(), [
            "product_id" => "required",
            "text" => "required|min:10|max:255"
        ]);

        if ($validate->fails()) {
            return response()->json($validate->errors(), Response::HTTP_BAD_REQUEST);
        } else {
            $requestData = $request->all();

            $user = new Ulti();
            $userId = $user->getAuthenticatedUser();

            $comment = new Comment();

            $comment->user_id = $userId;
            $comment->product_id = $requestData['product_id'];
            $comment->text = $requestData['text'];

            if ($comment->save()) {
                return response()->json($comment, Response::HTTP_OK);
            } else {
                return response()->json(["message" => "Store faild"], Response::HTTP_ACCEPTED);
            }
        }
    }
}
