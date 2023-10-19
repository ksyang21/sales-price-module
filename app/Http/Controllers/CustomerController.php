<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\DriverCustomer;
use App\Models\Order;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Inertia\Inertia;

class CustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): \Inertia\Response
    {
        $customers = Customer::all();
        foreach ($customers as &$customer) {
            $driver_customer    = DriverCustomer::where('customer_id', $customer->id)->first();
            $driver             = $driver_customer->driver ?? [];
            $customer['driver'] = $driver;
        }
        return Inertia::render('Admin/CustomerManagement', [
            'customers' => $customers,
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
    public function store(Request $request): \Inertia\Response|\Illuminate\Http\RedirectResponse
    {
        $validator = Validator::make($request->all(), [
            'name'    => [
                'required',
                'string',
                'max:255',
                Rule::unique('customers', 'name'),
            ],
            'address' => 'required|string',
        ]);

        if ($validator->fails()) {
            return Inertia::render('Admin/AddCustomer', [
                'errors' => $validator->errors()->all(),
            ]);
        }

        $data = $validator->getData();

        $customer = Customer::create([
            'name'    => $data['name'],
            'address' => $data['address'],
        ]);

        return Redirect::route('customer_management')->with('success', 'Customer added!');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $customer           = Customer::find($id);
        $prices             = $customer->prices;
        $driver_customer    = $customer->driverCustomer;
        $customer['driver'] = $driver_customer->driver ?? [];
        $all_drivers        = User::whereHas('roles', function ($query) {
            $query->where('name', 'driver');
        })->get();
        foreach ($prices as &$price) {
            $price['product'] = $price->product;
        }

        return Inertia::render('Admin/CustomerDetails', [
            'customer' => $customer,
            'prices'   => $prices,
            'drivers'  => $all_drivers,
            'driverCustomer' => $driver_customer ?? [],
        ]);
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
        if ($customer) {
            $customer->delete();
            return Redirect::route('customer_management')->with('success', 'Customer removed');
        } else {
            return Redirect::route('customer_management')->with('error', 'Invalid customer ID');
        }
    }

    public function getCustomerDetails(string $id) {
        $pending_orders = [];
        $customer = Customer::find($id);
        $driver = Auth::user();
        $driver_customer = DriverCustomer::where('customer_id', $customer->id)->where('driver_id', $driver->id)->first(); // To make sure customer is under current driver
        if($driver_customer) {
            $pending_orders = Order::where('driver_id', $driver->id)->where('customer_id', $customer->id)->where('status', 'pending')->get();
        }
        return Inertia::render('Frontend/CustomerDetails', [
            'customer' => $customer,
            'pendingOrders' => $pending_orders
        ]);
    }
}
