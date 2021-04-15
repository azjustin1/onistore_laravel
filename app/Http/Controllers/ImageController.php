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
        $image = Image::with("product")->get();
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
            "product_id" => "required"
        ]);

        if ($validate->fails()) {
            return response()->json($validate->errors(), Response::HTTP_BAD_REQUEST);
        } else {
            $files = $request->file('images');
            if (isset($files)) {
                if($request->hasFile('images')) {
                    foreach($files as $file) {
                        $name = $file->getClientOriginalName();
                        $destinationPath = public_path('/images');
                        $file->move($destinationPath, $name);
                        $image = new Image();
                        $image->product_id = $request->product_id;
                        $image->url = url("images/" . $name);
                        $image->save();
                    }
                }
                return response()->json(["message" => "Ok"], Response::HTTP_OK);
            } else {
                return response()->json(["message" => "Store failed"], Response::HTTP_BAD_GATEWAY);
            }
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Image  $image
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        $image = Image::with("product")->find($id);
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
    public function destroy($id)
    {
        $imageData = Image::with("product")->find($id);
        if (!isset($imageData)) {
            return response()->json(["message" => "Not found"], Response::HTTP_NOT_FOUND);
        } else {
            try {
                if ($imageData->delete()) {
                    return response()->json(["message" => "Delete Successfully"], Response::HTTP_OK);
                } else {
                    return response()->json(["message" => "Delete failed"], Response::HTTP_NOT_FOUND);
                }
            } catch (\Exception $e) {
                return response()->json(["message" => $e->getMessage()]);
            }
        }
    }

    public function uploadTest(Request $request) {

        $url = "";
        $files = $request->file('images');
        if($request->hasFile('images')) {
            foreach($files as $file) {
                $name = $file->getClientOriginalName();
                $destinationPath = public_path('\\images');
                $file->move($destinationPath, $name);
                $url = url("images/" . $name);
            }
        }
        return response()->json($url);
    }
}
