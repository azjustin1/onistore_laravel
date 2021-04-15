<?php

namespace App\Http\Controllers;

use App\Models\Image;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;

class ImageController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $image = Image::all();
        return response()->json($image, Response::HTTP_OK)
            ->header('X-Total-Count', Image::all()->count())
            ->header("Access-Control-Expose-Headers", "X-Total-Count");
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
            "url" => "required|max:255"
        ]);

        if ($validate->fails()) {
            return response()->json($validate->errors(), Response::HTTP_BAD_REQUEST);
        } else {
            $image = new Image();
            $image->product_id = $request->product_id;
            $image->url = $request->url;
            if ($image->save()) {
                return response()->json($image, Response::HTTP_OK);
            } else {
                return response()->json(["message" => "Store failed"], Response::HTTP_BAD_GATEWAY);
            }
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Image  $image
     * @return \Illuminate\Http\Response
     */
    public function show(Image $image)
    {
        return response()->json($image, Response::HTTP_OK);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Image  $image
     * @return \Illuminate\Http\Response
     */
    public function edit(Image $image)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Image  $image
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Image $image)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Models\Image $image
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function destroy(Image $image)
    {
        if ($image->delete()) {
            return response()->json($image, Response::HTTP_OK);
        }
    }
}
