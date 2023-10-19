<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderDetails;
use Illuminate\Http\Request;
use Inertia\Inertia;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $orders = Order::all();
        foreach($orders as &$order) {
            $order['total_prices'] = 0;
            $order['driver'] = $order->driver;
            $order['customer'] = $order->customer;
            $order['details'] = $order->details;
            foreach($order['details'] as &$detail) {
                $detail['product'] = $detail->product;
                $order['total_prices'] += $detail->price * $detail->quantity;
            }
        }
        return Inertia::render('Admin/OrdersListing', [
            'orders' => $orders
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $order = Order::find($id);
        $order_details = OrderDetails::where('order_id', $id)->get();
        foreach($order_details as &$detail) {
            $detail['product'] = $detail->product;
        }
        return Inertia::render('Admin/OrderDetails', [
            'order' => $order,
            'details' => $order_details
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Order $order)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Order $order)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Order $order)
    {
        //
    }

    public function completeOrder(Order $order) {
        $data = [
            'status' => 'completed'
        ];
        $order->update($data);

        $orders = Order::where('driver_id', $order->driver_id)->get();
        foreach($orders as &$order) {
            $order['total_price'] = 0;
            $order['details'] = $order->details;
            $order['customer'] = $order->customer;
            foreach($order['details'] as $detail) {
                $order['total_price'] += $detail['price'];
            }
        }
        return response()->json([
            'status' => 1,
            'data' => [
                'msg' => 'Order completed!',
                'orders' => $orders
            ]
        ]);
    }
}
