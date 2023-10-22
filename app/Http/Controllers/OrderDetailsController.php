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

        $quantity      = intval($data['quantity']);
        $order_details = OrderDetails::find($data['id']);
        $order         = $order_details->order;
        $product       = $order_details->product;

        $price = Price::where('product_id', $product->id)->where('customer_id', $order->customer_id)->first();

        // Delete all order details with this product
        if ($price) {
            OrderDetails::where('product_id', $product->id)->where('order_id', $order->id)->delete();
            if ($price->type === 'foc module') { // foc module
//                $price    = $quantity * $price->price;
                OrderDetails::create([
                    'order_id'   => $order->id,
                    'product_id' => $product['id'],
                    'price'      => $price->price,
                    'quantity'   => $quantity,
                    'is_foc'     => 0,
                ]);
                $foc_gift = intval($quantity / $price->foc_quantity) * $price->foc_gift;
                if ($foc_gift > 0) {
                    OrderDetails::create([
                        'order_id'   => $order->id,
                        'product_id' => $product['id'],
                        'price'      => 0,
                        'quantity'   => $foc_gift,
                        'is_foc'     => 1,
                    ]);
                }
            } else { // special price module
                if ($quantity <= $price->max_stock) {
                    $price = $quantity * $price->price;
                    OrderDetails::create([
                        'order_id'   => $order->id,
                        'product_id' => $product['id'],
                        'price'      => $price,
                        'quantity'   => $quantity,
                        'is_foc'     => 0,
                    ]);
                } else {
                    $special_price    = $price->price;
                    $special_quantity = $price->max_stock;
                    OrderDetails::create([
                        'order_id'   => $order->id,
                        'product_id' => $product['id'],
                        'price'      => $special_price,
                        'quantity'   => $special_quantity,
                        'is_foc'     => 0,
                    ]);

                    $original_quantity = $quantity - $price->max_stock;
                    $original_price    = $product->price;
                    OrderDetails::create([
                        'order_id'   => $order->id,
                        'product_id' => $product['id'],
                        'price'      => $original_price,
                        'quantity'   => $original_quantity,
                        'is_foc'     => 0,
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
        $order_detail          = OrderDetails::find($id);
        $order_id              = $order_detail->order_id;
        $product_id            = $order_detail->product_id;
        $order_detail_quantity = $order_detail->quantity;

        $order       = $order_detail->order;
        $customer_id = $order->customer_id;

        $all_order_details_of_product = OrderDetails::where('product_id', $product_id)->where('order_id', $order_id)->get();
        if (count($all_order_details_of_product) < 2) {
            $order_detail->delete();
        } else {
            $total_quantity = 0;
            foreach ($all_order_details_of_product as $item) {
                if (intval($item->is_foc) === 0) {
                    $total_quantity += $item->quantity;
                }
            }
            $price   = Price::where('product_id', $product_id)->where('customer_id', $customer_id)->first();
            $product = $price->product;

            OrderDetails::where('product_id', $product_id)->where('order_id', $order_id)->delete();
            $balance = $total_quantity - $order_detail_quantity;
            if ($balance > 0) {
                if ($price) {
                    if ($price->type === 'foc module') { // foc module
                        OrderDetails::create([
                            'order_id'   => $order_id,
                            'product_id' => $product_id,
                            'price'      => $product->price,
                            'quantity'   => $balance,
                            'is_foc'     => 0,
                        ]);
                        $foc_gift = intval($balance / $price->foc_quantity) * $price->foc_gift;
                        if ($foc_gift > 0) {
                            OrderDetails::create([
                                'order_id'   => $order_id,
                                'product_id' => $product_id,
                                'price'      => 0,
                                'quantity'   => $foc_gift,
                                'is_foc'     => 1,
                            ]);
                        }
                    } else { // special price module
                        if ($balance <= $price->max_stock) {
                            $price = $balance * $price->price;
                            OrderDetails::create([
                                'order_id'   => $order_id,
                                'product_id' => $product_id,
                                'price'      => $price,
                                'quantity'   => $balance,
                                'is_foc'     => 0,
                            ]);
                        } else {
                            $special_price    = $price->price;
                            $special_quantity = $price->max_stock;
                            OrderDetails::create([
                                'order_id'   => $order_id,
                                'product_id' => $product_id,
                                'price'      => $special_price,
                                'quantity'   => $special_quantity,
                                'is_foc'     => 0,
                            ]);

                            $original_quantity = $balance - $price->max_stock;
                            $original_price    = $product->price;
                            OrderDetails::create([
                                'order_id'   => $order_id,
                                'product_id' => $product_id,
                                'price'      => $original_price,
                                'quantity'   => $original_quantity,
                                'is_foc'     => 0,
                            ]);
                        }
                    }
                } else {
                    OrderDetails::create([
                        'order_id'   => $order_id,
                        'product_id' => $product_id,
                        'price'      => $product->price,
                        'quantity'   => $balance,
                        'is_foc'     => 0,
                    ]);
                }
            }
        }


        return Redirect::route('confirm_order', $order_id);

    }
}
