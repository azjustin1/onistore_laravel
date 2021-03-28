<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use App\Http\Middleware\Slugify;
use Illuminate\Http\Response;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $product = Product::all();
        return response()->json($product, Response::HTTP_OK)->header('X-Total-Count', Product::all()->count())->header("Access-Control-Expose-Headers", "X-Total-Count");
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
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $product = new Product();
        $slug = new Slugify();
        $product->name = $request->name;
        $product->description = $request->description;
        $product->amount = $request->amount;
        $product->slug = $slug->Slug($product->name);
        if ($product->save()) {
            return response()->json($product);
        } else {
            return response()->json(["message" => "Store faild"], Response::HTTP_BAD_GATEWAY);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function show(Product $product)
    {
//        $product_result = Product::findOrFail($id);
//        return new ProductResource($product_result);
        return response()->json($product, Response::HTTP_OK);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function edit(Product $product)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Product $product)
    {
        return $product->update($request->all());
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function destroy(Product $product)
    {
        if ($product->delete()) {
            return response()->json($product, Response::HTTP_OK);
        }

    }
}
