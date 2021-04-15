<?php

namespace App\Http\Controllers;

use App\Http\Middleware\Slugify;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $category = Category::all();
        return response()->json($category, Response::HTTP_OK)
            ->header('X-Total-Count', Category::all()->count())
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
//        $validate = Validator::make($request->all(), [
//            "name" => "required|max:255",
//            "description" => "required|min:10|max:255"
//        ]);
//
//        if ($validate->fails()) {
//            return response()->json($validate->errors(), Response::HTTP_BAD_REQUEST);
//        } else {
//
//            $category = new Category();
//            $slug = new Slugify();
//            $category->name = $request->name;
//            $category->description = $request->description;
//            $category->slug = $slug->Slug($category->name);
//            if ($category->save()) {
//                return response()->json($category, Response::HTTP_OK);
//            } else {
//                return response()->json(["message" => "Store failed"], Response::HTTP_BAD_GATEWAY);
//            }
//        }
    }

    /**
     * Display the specified resource.
     *
     * @param \App\Models\Category $category
     * @return \Illuminate\Http\Response
     */
    public function show(Category $category)
    {
//        return response()->json($category, Response::HTTP_OK);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\Models\Category $category
     * @return \Illuminate\Http\Response
     */
    public function edit(Category $category)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Category $category
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, Category $category)
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
//            if ($category->update($requestData)) {
//                return response()->json($category, Response::HTTP_OK);
//            } else {
//                return response()->json(["message" => "Update failed"], Response::HTTP_BAD_REQUEST);
//            }
//        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Models\Category $category
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Category $category)
    {
//        if ($category->delete()) {
//            return response()->json($category, Response::HTTP_OK);
//        }
    }

    public function adminIndex()
    {
        $category = Category::all();
        return response()->json($category, Response::HTTP_OK)
            ->header('X-Total-Count', Category::all()->count())
            ->header("Access-Control-Expose-Headers", "X-Total-Count");
    }

    public function adminEdit(Request $request, $id)
    {
        $validate = Validator::make($request->all(), [
            "name" => "required|max:255"
        ]);

        $category = DB::table('categories')->where('id', $id);

        if ($validate->fails()) {
            return response()->json($validate->errors(), Response::HTTP_BAD_REQUEST);
        } else {
            $slug = new Slugify();
            $requestData = $request->all();
            $requestData['slug'] = $slug->Slug($request['name']);
            if ($category->update($requestData)) {
                return response()->json(["message" => "Update Successfully"], Response::HTTP_OK);
            } else {
                return response()->json(["message" => "Update failed"], Response::HTTP_BAD_REQUEST);
            }
        }
    }

    public function adminShow($id)
    {
        $categoryData = DB::table('categories')->where('id', $id)->first();
        if (empty($categoryData)) {
            return response()->json(["message" => "Not found"], Response::HTTP_NOT_FOUND);
        } else {
            return response()->json($categoryData, Response::HTTP_OK);
        }
    }

    public function adminDelete($id): \Illuminate\Http\JsonResponse
    {
        $categoryData = Category::with("product")->find($id);
        if (!isset($categoryData)) {
            return response()->json(["message" => "Not found"], Response::HTTP_NOT_FOUND);
        } else {
            try {
                if ($categoryData->delete()) {
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
            "description" => "required|min:10|max:255"
        ]);

        if ($validate->fails()) {
            return response()->json($validate->errors(), Response::HTTP_BAD_REQUEST);
        } else {
            $category = new Category();
            $slug = new Slugify();
            $category->name = $request->name;
            $category->description = $request->description;
            $category->slug = $slug->Slug($category->name);
            if ($category->save()) {
                return response()->json($category, Response::HTTP_OK);
            } else {
                return response()->json(["message" => "Store failed"], Response::HTTP_BAD_GATEWAY);
            }
        }
    }
}
