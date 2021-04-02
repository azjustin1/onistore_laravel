<?php

namespace App\Http\Controllers;

use App\Http\Middleware\Ulti;
use App\Models\Product;
use App\Models\Rating;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;

class RatingController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $product = Product::with("rating")->get();
        return response()->json($product, Response::HTTP_OK);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $validate = Validator::make($request->all(), [
            "product_id" => "required",
            "rate" => "required|integer|min:1|max:5"
        ]);

        if ($validate->fails()) {
            return response()->json($validate->errors(), Response::HTTP_BAD_REQUEST);
        } else {
            $requestData = $request->all();
            $user = new Ulti();
            $userId = $user->getAuthenticatedUser();

            $rating = Rating::with("user")
                ->where("user_id", $userId)
                ->where("product_id", $requestData["product_id"])
                ->first();

            if ($rating === null) {
//            $product = Product::with("image")->where("id", $requestData['product_id'])->first();
                $rating = new Rating();

                $rating->user_id = $userId;
                $rating->product_id = $requestData['product_id'];
                $rating->rate = $request['rate'];

                if ($rating->save()) {
                    return response()->json($rating, Response::HTTP_CREATED);
                } else {
                    return response()->json(["message" => "Store faild"], Response::HTTP_ACCEPTED);
                }
            } else {
                return response()->json(["message" => "You have rated before"], Response::HTTP_ACCEPTED);
            }
        }

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Rating  $rating
     * @return \Illuminate\Http\Response
     */
    public function show(Rating $rating)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Rating  $rating
     * @return \Illuminate\Http\Response
     */
    public function edit(Rating $rating)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Rating  $rating
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Rating $rating)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Rating  $rating
     * @return \Illuminate\Http\Response
     */
    public function destroy(Rating $rating)
    {
        //
    }
}
