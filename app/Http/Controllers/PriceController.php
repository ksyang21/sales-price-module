<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Price;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
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
        $product = Product::find($id);
        $customers_without_prices = Customer::whereDoesntHave('prices', function ($query) use ($product) {
            $query->where('product_id', $product->id);
        })->get();

        return Inertia::render('Admin/AddPrice', [
            'product' => $product,
            'customers' => $customers_without_prices,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated_date = $request->validate([
            'product' => 'required|numeric|min:0|not_in:0',
            'customer' => 'required|numeric|min:0|not_in:0',
            'price' => 'required|numeric|min:0',
            'max_stock' => 'required|numeric|min:0',
        ]);

        $price = Price::create([
            'product_id' => $validated_date['product'],
            'customer_id' => $validated_date['customer'],
            'price' => $validated_date['price'],
            'max_stock' => $validated_date['max_stock']
        ]);

        return Redirect::route('product.show', $validated_date['product'])->with('success', 'Price added!');
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
