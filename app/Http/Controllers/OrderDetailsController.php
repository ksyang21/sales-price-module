<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderDetails;
use App\Models\Price;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;
use Inertia\Inertia;

class OrderDetailsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
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
    public function show(OrderDetails $orderDetails)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(OrderDetails $orderDetails)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, OrderDetails $orderDetails)
    {
        $validator = Validator::make($request->all(), [
            'id'       => 'required|numeric',
            'quantity' => 'required|numeric|min:0',
        ]);

        if ($validator->fails()) {
            return Inertia::render('Frontend/ConfirmOrder', [
                'errors' => $validator->errors()->all(),
            ]);
        }

        $data = $validator->getData();

        $quantity = intval($data['quantity']);
        $order_details = OrderDetails::find($data['id']);
        $order = $order_details->order;
        $product = $order_details->product;

        $price = Price::where('product_id', $product->id)->where('customer_id', $order->customer_id)->first();

        // Delete all order details with this product
        if ($price) {
            OrderDetails::where('product_id', $product->id)->delete();
            if ($price->type === 'foc module') { // foc module
                $quantity = $quantity + (intval($quantity / $price->foc_quantity) * $price->foc_gift);
                $price    = $quantity * $price->price;
                OrderDetails::create([
                    'order_id'   => $order->id,
                    'product_id' => $product['id'],
                    'price'      => $price,
                    'quantity'   => $quantity,
                ]);
            } else { // special price module
                if ($quantity <= $price->max_stock) {
                    $price    = $quantity * $price->price;
                    OrderDetails::create([
                        'order_id'   => $order->id,
                        'product_id' => $product['id'],
                        'price'      => $price,
                        'quantity'   => $quantity,
                    ]);
                } else {
                    $special_price    = $price->price;
                    $special_quantity = $price->max_stock;
                    OrderDetails::create([
                        'order_id'   => $order->id,
                        'product_id' => $product['id'],
                        'price'      => $special_price,
                        'quantity'   => $special_quantity,
                    ]);

                    $original_quantity = $quantity - $price->max_stock;
                    $original_price    = $product->price;
                    OrderDetails::create([
                        'order_id'   => $order->id,
                        'product_id' => $product['id'],
                        'price'      => $original_price,
                        'quantity'   => $original_quantity,
                    ]);
                }
            }
        } else {
            $order_details->update(['quantity', $quantity]);
        }

        return Redirect::route('confirm_order', $order->id)->with('success', 'Item updated!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $order_detail = OrderDetails::find($id);
        $order_id     = $order_detail->order_id;
        $order_detail->delete();

        return Redirect::route('confirm_order', $order_id);

    }
}
