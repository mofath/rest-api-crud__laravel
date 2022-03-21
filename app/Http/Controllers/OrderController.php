<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     * 
     * @return \Illuminate\Http\Response 
     */
    public function index()
    {
        $orders = Order::latest()->paginate(10);
        return response()->json([
            'success' => true,
            'data' => $orders,
            'message' => 'Orders fetched successfully'
        ], 404);
    }

    /**
     * Display the specified resource.
     * 
     * @return \Illuminate\Http\Response 
     */
    public function getDetails(Request $request)
    {
        $found = Order::where('id', $request->id)->get();
        if ($found->isEmpty()) {
            return response()->json(
                [
                    'success' => false,
                    'message' => 'Order not found'
                ],
                404
            );
        }

        return response()->json(
            [
                'success' => true,
                'message' => 'Order fetched successfully!',
                'user' => $found
            ],
            200
        );
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|integer',
            'price' => 'required',
            'address' => 'required|string',
        ]);
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 401);
        }

        $user_found = User::where('id', $request->user_id)->get();
        if ($user_found->isEmpty()) {
            return response()->json(
                [
                    'success' => false,
                    'message' => 'User not found'
                ],
                404
            );
        }

        $order = new Order();
        $order->user_id = $request->user_id;
        $order->price = $request->price;
        $order->address = $request->address;
        $order->save();

        return response()->json(
            [
                'success' => true,
                'message' => 'Order updated successfully!',
                'Order' => $order
            ],
            201
        );
    }


    /**
     * Update the specified resource in storage.
     * 
     * @return \Illuminate\Http\Response 
     */
    public function update(Request $request)
    {
        $found = Order::find($request->id);
        if ($found->isEmpty()) {
            return response()->json(['message' => 'Order not found'], 404);
        }

        $validator = Validator::make($request->all(), [
            'address' => 'required|string',
            'user_id' => 'required|intger',
            'price' => 'required|intger',
        ]);
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }

        $found->update($request->all());

        return response()->json(
            [
                'success' => true,
                'message' => 'Order updated successfully!',
                'Order' => $found
            ],
            201
        );
    }

    /**
     * Remove the specified resource from storage.
     * 
     * @return \Illuminate\Http\Response 
     */
    public function destroy(Request $request)
    {
        $found = Order::find($request->id);
        if (!$found) {
            return response()->json(['message' => 'Order not found'], 404);
        }

        $found->delete();

        return response()->json(
            [
                'success' => true,
                'message' => 'Order deleted successfully'
            ],
            404
        );
    }
}
