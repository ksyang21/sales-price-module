<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;
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
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
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
}
