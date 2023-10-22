<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
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
    public function store(Request $request): \Inertia\Response|\Illuminate\Http\RedirectResponse
    {
        $validator = Validator::make($request->all(), [
            'name' => ['required','string','max:255', Rule::unique('products', 'name')],
            'price' => 'required|numeric'
        ]);

        if($validator->fails()) {
            return Inertia::render('Admin/AddProduct', [
                'errors' => $validator->errors()->all()
            ]);
        }

        $data = $validator->getData();

        $product = Product::create([
            'name' => $data['name'],
            'price' => $data['price'],
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
    public function edit(string $id)
    {
        $product = Product::find($id);
        return Inertia::render('Admin/EditProduct', [
            'product' => $product
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Product $product)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required|numeric|min:0',
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0'
        ]);

        if($validator->fails()) {
            $product = Product::find($request['id']);
            return Inertia::render('Admin/EditProduct', [
                'errors' => $validator->errors()->all(),
                'product' => $product
            ]);
        }

        $data = $validator->getData();
        $product = Product::find($data['id']);
        $product->update([
            'name' => $data['name'],
            'price' => $data['price']
        ]);

        return Redirect::route('products')->with('success', 'Product details updated!');
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
