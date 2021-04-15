<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\CategoryProduct;
use App\Models\Comment;
use App\Models\Image;
use App\Models\Order;
use App\Models\Product;
use App\Models\Rating;
use Illuminate\Http\Request;
use App\Http\Middleware\Slugify;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use function PHPUnit\Framework\isEmpty;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $product = Product::with(["category", "image", "rating", "comment"])->get();
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
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Product $product)
    {
        return \response()->json(["message" => "Unauthorized"], Response::HTTP_FORBIDDEN);
    }


    /**
     * Display the specified resource.
     *
     * @param \App\Models\Product $product
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Product $product)
    {
        $product::with(["category", "image", "comment", "rating"])->get();
        return response()->json($product, Response::HTTP_OK);
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Models\Product $product
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Product $product)
    {
    }

    public function adminIndex()
    {
        $product = Product::with(["category", "image", "comment", "rating"])->get();
        return response()->json($product, Response::HTTP_OK)
            ->header('X-Total-Count', Product::all()->count())
            ->header("Access-Control-Expose-Headers", "X-Total-Count");
    }

    public function adminShow($id) {
        $productData = Product::with(["category", "image", "comment", "rating"])->find($id);
        if (!isset($productData)) {
            return response()->json(["message" => "Not found"], Response::HTTP_NOT_FOUND);
        } else {
            return response()->json($productData, Response::HTTP_OK);
        }
    }

    public function adminEdit(Request $request, $id) {
        $validate = Validator::make($request->all(), [
            "name" => "required|max:255",
            "description" => "required|min:10|max:255",
            "price" => "required",
            "fake_price" => "required",
            "quantity" => "required"
        ]);

        if ($validate->fails()) {
            return response()->json($validate->errors(), Response::HTTP_BAD_REQUEST);
        } else {
            $slug = new Slugify();
            $product = Product::with("image")->find($id);
            if (!isset($product)) {
                return response()->json(["message" => "Not found"], Response::HTTP_NOT_FOUND);
            }
            $product->name = $request["name"];
            $product->description = $request["description"];
            $product->quantity = $request["quantity"];
            $product->price = $request["price"];
            $product->fake_price = $request["fake_price"];
            $product->slug = $slug->Slug($product->name);
            if ($product->save()) {
                return response()->json(["message" => "Update Successfully"], Response::HTTP_OK);
            } else {
                return response()->json(["message" => "Update failed"], Response::HTTP_BAD_REQUEST);
            }
        }
    }

    public function adminDelete($id) {
        $productData = Product::with("image")->find($id);

        if (!isset($productData)) {
            return response()->json(["message" => "Not found"], Response::HTTP_NOT_FOUND);
        } else {
            try {
                if ($productData->delete()) {
                    return response()->json(["message" => "Delete Successfully"], Response::HTTP_OK);
                } else {
                    return response()->json(["message" => "Delete failed"], Response::HTTP_NOT_FOUND);
                }
            } catch (\Exception $e) {
                return response()->json(["message" => $e->getMessage()]);
            }
        }
    }

    public function adminCreate(Request $request)
    {
        $validate = Validator::make($request->all(), [
            "name" => "required|max:255",
            "description" => "required|min:10|max:255",
            "price" => "required",
            "fake_price" => "required",
            "quantity" => "required"
        ]);

        if ($validate->fails()) {
            return response()->json($validate->errors(), Response::HTTP_BAD_REQUEST);
        } else {
            $product = new Product();
            $slug = new Slugify();
            $product->name = $request["name"];
            $product->description = $request["description"];
            $product->quantity = $request["quantity"];
            $product->price = $request["price"];
            $product->fake_price = $request["fake_price"];
            $product->slug = $slug->Slug($product->name);
            if ($product->save()) {
                $listCategory = $request["categories"];
                if (isset($listCategory)) {
                    foreach ($listCategory as $item){
                        $productCategory = new CategoryProduct();
                        $productCategory->category_id = $item["id"];
                        $productCategory->product_id = $product->id;
                        $productCategory->save();
                    }
                } else {
                    return response()->json(["message" => "Store category failed"], Response::HTTP_BAD_GATEWAY);
                }
                $files = $request->file('images');
                if (isset($files)) {
                    if($request->hasFile('images')) {
                        foreach($files as $file) {
                            $name = $file->getClientOriginalName();
                            $destinationPath = public_path('/images');
                            $file->move($destinationPath, $name);
                            $image = new Image();
                            $image->product_id = $product->id;
                            $image->url = url("images/" . $name);
                            $image->save();
                        }
                    }
                }
                return response()->json($product, Response::HTTP_OK);
            } else {
                return response()->json(["message" => "Store failed"], Response::HTTP_BAD_GATEWAY);
            }
        }
    }

    public function getSearch(Request $request) {
        $product = Product::with(["image", "category"])
            ->where("name", "like", "%" . $request["search"] . "%")
            ->orWhere("price", $request["search"])->get();

        return response()->json($product, Response::HTTP_OK);
    }

    public function getNumberOfProduct() {
        $product = Product::all()->count();
        $category = Category::all()->count();
        $order = Order::all()->count();
        return response()->json([
            "product_items" => $product,
            "category_items" => $category,
            "order_items" => $order
            ]);
    }

    public function uploadTest(Request $request) {

        $a = "";
        $files = $request->file('images');
        if($request->hasFile('images')) {
            foreach($files as $file) {
                $name = $file->getClientOriginalName();
                $destinationPath = public_path('\\images');
                $file->move($destinationPath, $name);
                $a = $destinationPath . "\\" . $name;
                $url = url("images/" . $name);

            }
        }
        return response()->json($a);
    }
}
