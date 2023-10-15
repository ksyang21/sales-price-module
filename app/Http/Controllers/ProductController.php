<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): \Inertia\Response
    {
        $products = Product::all();
        return Inertia::render('Admin/ProductListing', [
            'products' => $products
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): \Inertia\Response
    {
        return Inertia::render('Admin/AddProduct');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): \Illuminate\Http\RedirectResponse
    {
        $validated_data = $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric'
        ]);

        $product = Product::create([
            'name' => $validated_data['name'],
            'price' => $validated_data['price'],
        ]);

        return Redirect::route('products')->with('success', 'New Product added!');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $product = Product::find($id);
        $prices = $product->prices;
        foreach($prices as &$price) {
            $price['customer'] = $price->customer;
        }
        return Inertia::render('Admin/ProductDetails', [
            'product' => $product,
            'prices' => $prices
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Product $product)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Product $product)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $product = Product::find($id);
        if($product) {
            $product->delete();
            return Redirect::route('products')->with('success', 'Product removed!');
        } else {
            return Redirect::route('products')->with('error', 'Invalid product ID');
        }
    }
}
