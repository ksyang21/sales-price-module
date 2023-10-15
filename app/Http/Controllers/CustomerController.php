<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;

class CustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): \Inertia\Response
    {
        $customers = Customer::all();
        return Inertia::render('Admin/CustomerManagement', [
            'customers' => $customers
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): \Inertia\Response
    {
        return Inertia::render('Admin/AddCustomer');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated_date = $request->validate([
            'name' => 'required|string|max:255'
        ]);

        $customer = Customer::create([
            'name' => $validated_date['name']
        ]);

        return Redirect::route('customer_management')->with('success', 'Customer added!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Customer $customer)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Customer $customer)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Customer $customer)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $customer = Customer::find($id);
        if($customer) {
            $customer->delete();
            return Redirect::route('customer_management')->with('success', 'Customer removed');
        } else {
            return Redirect::route('customer_management')->with('error', 'Invalid customer ID');
        }
    }
}
