<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\DriverCustomer;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Inertia\Inertia;

class DriverCustomerController extends Controller
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
        $validator = Validator::make($request->all(), [
            'driver_id' => 'required|numeric|min:1',
            'customer_id' => 'required|numeric|min:1',
        ]);

        if ($validator->fails()) {
            $customer           = Customer::find($validator->getData()['customer_id']);
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
                'errors' => $validator->errors()->all(),
            ]);
        }

        $data = $validator->getData();

        DriverCustomer::create([
            'driver_id' => $data['driver_id'],
            'customer_id' => $data['customer_id'],
        ]);

        return Redirect::route('customer_management')->with('success', 'Driver assigned!');
    }

    /**
     * Display the specified resource.
     */
    public function show(DriverCustomer $driverCustomer)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(DriverCustomer $driverCustomer)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, DriverCustomer $driverCustomer)
    {
        $validator = Validator::make($request->all(), [
            'driver_id' => 'required|numeric|min:1',
            'customer_id' => 'required|numeric|min:1',
        ]);

        if ($validator->fails()) {
            $customer           = Customer::find($validator->getData()['customer_id']);
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
                'errors' => $validator->errors()->all(),
            ]);
        }

        $data = $validator->getData();
        $driverCustomer->update($data);

        return Redirect::route('customer_management')->with('success', 'Driver updated!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(DriverCustomer $driverCustomer)
    {
        //
    }
}
