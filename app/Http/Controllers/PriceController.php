<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Price;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

class PriceController extends Controller
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
    public function create(string $id)
    {
        $current_route = Route::current()->getName();
        if($current_route === 'price.product.create') {
            $product = Product::find($id);
            $customers_without_prices = Customer::whereDoesntHave('prices', function ($query) use ($product) {
                $query->where('product_id', $product->id);
            })->get();

            return Inertia::render('Admin/AddProductPrice', [
                'product' => $product,
                'customers' => $customers_without_prices,
            ]);
        } else {
            $customer = Customer::find($id);
//            $products_without_prices = Product::select('products.*')
//                ->leftJoin('prices', function ($join) use ($id) {
//                    $join->on('products.id', '=', 'prices.product_id')
//                        ->where('prices.customer_id', $id);
//                })
//                ->whereNull('prices.id')
//                ->get();
            $products_without_prices = Product::whereDoesntHave('prices', function ($query) use ($id) {
                $query->where('customer_id', $id);
            })->get();

            return Inertia::render('Admin/AddCustomerPrice', [
                'customer' => $customer,
                'products' => $products_without_prices
            ]);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated_data = $request->validate([
            'product' => 'required|numeric|min:0|not_in:0',
            'customer' => 'required|numeric|min:0|not_in:0',
            'price' => 'required|numeric|min:0',
            'max_stock' => 'required|numeric|min:0|max:100',
        ]);

        $price = Price::create([
            'product_id' => $validated_data['product'],
            'customer_id' => $validated_data['customer'],
            'price' => $validated_data['price'],
            'max_stock' => $validated_data['max_stock']
        ]);

        if($request->input('source') === 'product') {
            return Redirect::route('product.show', $validated_data['product'])->with('success', 'Price added!');
        } else {
            return Redirect::route('customer.show', $validated_data['customer'])->with('success', 'Price added!');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Price $price)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Price $price)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Price $price)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Price $price)
    {
        //
    }
}
