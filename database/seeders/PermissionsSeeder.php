<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class PermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Permission::create(['name'=>'create user']);
        Permission::create(['name'=>'edit user']);
        Permission::create(['name'=>'delete user']);
        Permission::create(['name'=>'set price']);
        Permission::create(['name'=>'view customer']);

        $admin_role = Role::create(['name' => 'admin']);
        $admin_role->givePermissionTo('create user');
        $admin_role->givePermissionTo('edit user');
        $admin_role->givePermissionTo('delete user');
        $admin_role->givePermissionTo('set price');
        $admin_role->givePermissionTo('view customer');

        $driver_role = Role::create(['name' => 'driver']);
        $driver_role->givePermissionTo('view customer');

        $admin = User::factory()->create([
            'name' => 'Admin',
            'email' => 'admin@example.com',
        ]);
        $admin->assignRole($admin_role);

        $drivers = User::factory()->count(4)->create();
        foreach($drivers as $driver) {
            $driver->assignRole($driver_role);
        }
        $driver = User::factory()->create([
            'name' => 'Driver 1',
            'email' => 'driver@example.com',
        ]);
        $driver->assignRole($driver_role);
    }

}
