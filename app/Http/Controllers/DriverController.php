<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\DriverCustomer;
use App\Models\Order;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Validator;
use Inertia\Inertia;
use Spatie\Permission\Models\Role;

class DriverController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): \Inertia\Response
    {
        $drivers = User::whereHas('roles', function ($query) {
            $query->where('name', 'driver');
        })->get();

        foreach($drivers as &$driver) {
            $driver_customers = DriverCustomer::where('driver_id', $driver->id)->get();
            $driver['customers'] = $driver_customers;
        }

        return Inertia::render('Admin/DriverManagement', [
            'drivers' => $drivers
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): \Inertia\Response
    {
        return Inertia::render('Admin/AddDriver');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): \Inertia\Response|\Illuminate\Http\RedirectResponse
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|unique:users',
            'password' => 'required|string|min:8',
        ]);

        if($validator->fails()) {
            return Inertia::render('Admin/AddDriver', [
                'errors' => $validator->errors()->all()
            ]);
        }

        $data = $validator->getData();
        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password'])
        ]);

        $driver_role = Role::where('name', 'driver')->first();
        $user->assignRole($driver_role);

        return Redirect::route('driver_management')->with('success', 'Driver added!');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $driver = User::find($id);
        $driver_customers = DriverCustomer::where('driver_id', $driver->id)->get();
        $customers = [];
        foreach($driver_customers as $driver_customer) {
            $customers[] = Customer::find($driver_customer->id);
        }
        $orders = Order::where('driver_id', $id)->get();
        foreach($orders as &$order) {
            $order['total_price'] = 0;
            $order['details'] = $order->details;
            $order['customer'] = $order->customer;
            foreach($order['details'] as $detail) {
                $order['total_price'] += $detail['price'];
            }
        }
        return Inertia::render('Admin/DriverDetails', [
            'orders' => $orders,
            'driver' => $driver,
            'customers' => $customers
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $driver = User::find($id);
        return Inertia::render('Admin/EditDriver', [
            'driver' => $driver
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required|numeric|min:0',
            'name' => 'required|string|max:255',
        ]);

        if($validator->fails()) {
            return Inertia::render('Admin/EditDriver', [
                'errors' => $validator->errors()->all()
            ]);
        }

        $data = $validator->getData();
        $user = User::find($data['id']);
        $user->update([
            'name' => $data['name'],
        ]);

        return Redirect::route('driver_management')->with('success', 'Driver name updated!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id): \Illuminate\Http\RedirectResponse
    {
        $driver = User::find($id);
        $driver->delete();
        return Redirect::route('driver_management')->with('success', 'Driver removed!');
    }

    public function listCustomers() {
        $driver = Auth::user();
        $driver_customers = DriverCustomer::where('driver_id', $driver->id)->get();
        $customers = [];
        foreach($driver_customers as $driver_customer) {
            $customers[] = Customer::find($driver_customer->id);
        }
        return Inertia::render('Frontend/Customers', [
            'driver' => $driver,
            'customers' => $customers
        ]);
    }

    public function listOrders() {
        $driver = Auth::user();
        $driver_customers = DriverCustomer::where('driver_id', $driver->id)->get();
        $customers = [];
        foreach($driver_customers as $driver_customer) {
            $customers[] = Customer::find($driver_customer->id);
        }
        $orders = Order::where('driver_id', $driver->id)->get();
        foreach($orders as &$order) {
            $order['total_price'] = 0;
            $order['details'] = $order->details;
            $order['customer'] = $order->customer;
            foreach($order['details'] as $detail) {
                $order['total_price'] += $detail['price'] * $detail['quantity'];
            }
        }
        return Inertia::render('Frontend/Orders', [
            'orders' => $orders,
            'driver' => $driver,
            'customers' => $customers
        ]);
    }
}
