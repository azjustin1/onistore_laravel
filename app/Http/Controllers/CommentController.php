<?php

namespace App\Http\Controllers;

use App\Http\Middleware\Ulti;
use App\Models\Comment;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class CommentController extends Controller
{

    public function store(Request $request)
    {
        $request->validate([
            'text' => 'required',
        ]);

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
