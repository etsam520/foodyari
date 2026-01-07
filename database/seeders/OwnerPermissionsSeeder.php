<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class OwnerPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create new permissions for owner management
        $permissions = [
            'owner.status',
            'owner.block', 
            'owner.unblock',
            'owner.access',
            'owner.restaurant-status',
            'owner.restaurant-block',
            'owner.restaurant-unblock',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate([
                'name' => $permission,
                'guard_name' => 'admin'
            ]);
        }

        // Assign permissions to admin role
        $adminRole = Role::where('name', 'admin')->where('guard_name', 'admin')->first();
        if ($adminRole) {
            $adminRole->givePermissionTo($permissions);
        }

        // You can also assign to other roles if needed
        // $superAdminRole = Role::where('name', 'super-admin')->first();
        // if ($superAdminRole) {
        //     $superAdminRole->givePermissionTo($permissions);
        // }
    }
}