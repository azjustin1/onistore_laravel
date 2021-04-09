<?php

namespace App\Http\Controllers;

use App\Http\Middleware\Ulti;
use App\Models\Order;
use App\Models\OrderProduct;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
        $requestData = $request->all();
        $listCart = $requestData["cart"];
        $user = new Ulti();
        $userId = $user->getAuthenticatedUser();

        $order = new Order();
        $order->user_id = $userId;
        $order->email = $requestData["email"];
        $order->phone = $requestData["phone"];
        $order->address = $requestData["address"];
        $order->note = $requestData["note"];

        if ($order->save()) {
            foreach ($listCart as $item) {
                $orderProduct = new OrderProduct();
                $orderProduct->order_id = $order->id;
                $orderProduct->product_id = $item["id"];
                $orderProduct->quantity = $item["quantity"];
                $orderProduct->price = $item["price"];
                $orderProduct->save();
            }
            return response()->json(["message" => "Order save successfully"], Response::HTTP_OK);
        } else {
            return response()->json(["message" => "Store faild"], Response::HTTP_ACCEPTED);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function show(Order $order)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function edit(Order $order)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Order $order)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function destroy(Order $order)
    {
        //
    }
}
