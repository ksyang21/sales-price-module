<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\DriverCustomer;
use App\Models\Order;
use App\Models\OrderDetails;
use App\Models\Price;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;
use Inertia\Inertia;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $orders = Order::all();
        foreach ($orders as &$order) {
            $order['total_prices'] = 0;
            $order['driver']       = $order->driver;
            $order['customer']     = $order->customer;
            $order['details']      = $order->details;
            foreach ($order['details'] as &$detail) {
                $detail['product']     = $detail->product;
                $order['total_prices'] += $detail->price * $detail->quantity;
            }
        }
        return Inertia::render('Admin/OrdersListing', [
            'orders' => $orders,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Customer $customer)
    {
        $products = Product::all();
        foreach ($products as &$product) {
            $price                    = Price::where('customer_id', $customer->id)->where('product_id', $product->id)->first();
            $product['special_price'] = $price ?? [];
        }
        return Inertia::render('Frontend/NewOrder', [
            'customer' => $customer,
            'products' => $products,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'customer_id' => 'required|numeric|min:1',
            'driver_id'   => 'required|numeric|min:1',
            'products'    => 'required|array',
        ]);

        if ($validator->fails()) {
            return Inertia::render('Frontend/NewOrder', [
                'errors' => $validator->errors()->all(),
            ]);
        }

        $data = $validator->getData();

        $customer_id = $data['customer_id'];
        $driver_id   = $data['driver_id'];
        $products    = $data['products'];

        // Create order with cart status
        $order = Order::create([
            'customer_id' => $customer_id,
            'driver_id'   => $driver_id,
            'status'      => 'cart',
        ]);
        foreach ($products as $product) {
            $total_quantity = $product['discount']['quantity'] + $product['original']['quantity'];
            $price          = Price::where('customer_id', $customer_id)->where('product_id', $product['id'])->first();
            if ($price) {
                if ($price->type === 'foc module') { // foc module
//                    $price    = $total_quantity * $price->price;
                    OrderDetails::create([
                        'order_id'   => $order->id,
                        'product_id' => $product['id'],
                        'price'      => $price->price,
                        'quantity'   => $total_quantity,
                        'is_foc'     => 0,
                    ]);
                    $foc_gift = intval($total_quantity / $price->foc_quantity) * $price->foc_gift;
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
                    if ($total_quantity <= $price->max_stock) {
                        $quantity = $total_quantity;
//                        $price    = $quantity * $price->price;
                        OrderDetails::create([
                            'order_id'   => $order->id,
                            'product_id' => $product['id'],
                            'price'      => $price->price,
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

                        $CurrentProduct    = $price->product;
                        $original_quantity = $total_quantity - $price->max_stock;
                        $original_price    = $CurrentProduct->price;
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
                $CurrentProduct = $price->product;
                OrderDetails::create([
                    'order_id'   => $order->id,
                    'product_id' => $product['id'],
                    'price'      => $CurrentProduct->price,
                    'quantity'   => $product['quantity'],
                    'is_foc'     => 0,
                ]);
            }
        }

        return Redirect::route('confirm_order', $order->id);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $order         = Order::find($id);
        $order_details = OrderDetails::where('order_id', $id)->get();
        foreach ($order_details as &$detail) {
            $detail['product'] = $detail->product;
        }
        return Inertia::render('Admin/OrderDetails', [
            'order'   => $order,
            'details' => $order_details,
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
    public function destroy(string $id): \Illuminate\Http\RedirectResponse
    {
        $order = Order::find($id);
        $order->delete();

        return Redirect::route('frontend_customers')->with('success', 'Order cancelled!');
    }

    public function completeOrder(Order $order): \Illuminate\Http\JsonResponse
    {
        $data = [
            'status' => 'completed',
        ];
        $order->update($data);

        $orders = Order::where('driver_id', $order->driver_id)->get();
        foreach ($orders as &$order) {
            $order['total_price'] = 0;
            $order['details']     = $order->details;
            $order['customer']    = $order->customer;
            foreach ($order['details'] as $detail) {
                $order['total_price'] += $detail['price'];
            }
        }
        return response()->json([
            'status' => 1,
            'data'   => [
                'msg'    => 'Order completed!',
                'orders' => $orders,
            ],
        ]);
    }

    public function confirmOrder(string $id): \Inertia\Response
    {
        $order         = Order::find($id);
        $order_details = OrderDetails::where('order_id', $order->id)->get();
        $customer      = $order->customer;
        $total_price   = 0;
        foreach ($order_details as &$detail) {
            $detail['product'] = $detail->product;
            $detail['special_price'] = Price::where('product_id', $detail['product']->id)->where('customer_id', $customer->id)->first();
            $total_price       += $detail->price * $detail->quantity;
        }
        return Inertia::render('Frontend/ConfirmOrder', [
            'order'    => $order,
            'details'  => $order_details,
            'customer' => $customer,
            'total'    => $total_price,
        ]);
    }

    public function payOrder(string $id)
    {
        $order = Order::find($id);
        $order->update([
            'status' => 'pending',
        ]);
        return Redirect::route('frontend_orders')->with('success', 'Order confirmed!');
    }
}
