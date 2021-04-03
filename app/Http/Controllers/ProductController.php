<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Product;
use App\Models\Rating;
use Illuminate\Http\Request;
use App\Http\Middleware\Slugify;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $product = Product::with("image")->get();
        return response()->json($product, Response::HTTP_OK)
            ->header('X-Total-Count', Product::all()->count())
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
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $validate = Validator::make($request->all(), [
            "name" => "required|max:255",
            "description" => "required|min:10|max:255"
        ]);

        if ($validate->fails()) {
            return response()->json($validate->errors(), Response::HTTP_BAD_REQUEST);
        } else {
            $product = new Product();
            $slug = new Slugify();
            $product->name = $request->name;
            $product->description = $request->description;
            $product->amount = $request->amount;
            $product->slug = $slug->Slug($product->name);
            if ($product->save()) {
                return response()->json($product, Response::HTTP_OK);
            } else {
                return response()->json(["message" => "Store failed"], Response::HTTP_BAD_GATEWAY);
            }
        }
    }

    /**
     * Display the specified resource.
     *
     * @param \App\Models\Product $product
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Product $product)
    {
        $comment = Comment::with("user")->where("product_id", $product["id"])->get();
        $rating = Rating::with("user")->where("product_id", $product["id"])->get();
        return response()->json([
            "products" => $product,
            "comments" => $comment,
            "ratings" => $rating
        ], Response::HTTP_OK);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\Models\Product $product
     * @return \Illuminate\Http\Response
     */
    public function edit(Product $product)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Product $product
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, Product $product)
    {
//        $validate = Validator::make($request->all(), [
//            "name" => "required|max:255"
//        ]);
//
//        if ($validate->fails()) {
//            return response()->json($validate->errors(), Response::HTTP_BAD_REQUEST);
//        } else {
//            $slug = new Slugify();
//            $requestData = $request->all();
//            $requestData['slug'] = $slug->Slug($request['name']);
//            if ($product->update($requestData)) {
//                return response()->json($product, Response::HTTP_OK);
//            } else {
//                return response()->json(["message" => "Update failed"], Response::HTTP_BAD_REQUEST);
//            }
//        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Models\Product $product
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Product $product)
    {
        if ($product->delete()) {
            return response()->json($product, Response::HTTP_OK);
        }
    }

    public function adminIndex()
    {
        $product = Product::all();
        return response()->json($product, Response::HTTP_OK)
            ->header('X-Total-Count', Product::all()->count())
            ->header("Access-Control-Expose-Headers", "X-Total-Count");
    }

    public function adminShow($id) {
        $productData = Product::with("image")->where("id", $id)->first();
        $comment = Comment::with("user")->where("product_id", $id)->get();
        $rating = Rating::with("user")->where("product_id", $id)->get();
        if (empty($productData)) {
            return response()->json(["message" => "Not found"], Response::HTTP_NOT_FOUND);
        } else {
            return response()->json([
                "products" => $productData,
                "comments" => $comment,
                "ratings" => $rating
            ], Response::HTTP_OK);
        }
    }

    public function adminEdit(Request $request, $id) {
        $validate = Validator::make($request->all(), [
            "name" => "required|max:255"
        ]);

        $product = DB::table('products')->where('id', $id);

        if ($validate->fails()) {
            return response()->json($validate->errors(), Response::HTTP_BAD_REQUEST);
        } else {
            $slug = new Slugify();
            $requestData = $request->all();
            $requestData['slug'] = $slug->Slug($request['name']);
            if ($product->update($requestData)) {
                return response()->json(["message" => "Update Successfully"], Response::HTTP_OK);
            } else {
                return response()->json(["message" => "Update failed"], Response::HTTP_BAD_REQUEST);
            }
        }
    }

    public function adminDelete($id) {
        $productData = Product::with("image")->where("id", $id)->first();
        if (empty($productData)) {
            return response()->json(["message" => "Not found"], Response::HTTP_NOT_FOUND);
        } else {
            if ($productData->delete()) {
                return response()->json(["message" => "Delete Successfully"], Response::HTTP_NOT_FOUND);
            } else {
                return response()->json(["message" => "Delete failed"], Response::HTTP_NOT_FOUND);
            }
        }
    }
}
