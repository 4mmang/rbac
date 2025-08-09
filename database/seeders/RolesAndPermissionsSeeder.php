<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // create super admin , create it role , assign super admin role to user , assign super admin permission
        $superAdmin = User::where('type', 'superadmin')->first();

        if (empty($superAdmin)) {
            $superAdmin = new User();
            $superAdmin->name = 'Super Admin';
            $superAdmin->email = 'superadmin@example.com';
            $superAdmin->password = Hash::make('12345678');
            $superAdmin->type = 'superadmin';
            $superAdmin->created_by = null;
            $superAdmin->save();

            $superAdminRole = Role::where('name', 'super admin')->first();
            if (empty($superAdminRole)) {
                $superAdminRole = Role::create([
                    'name' => 'super admin',
                    'created_by' => 0,
                ]);
            }
            $getSuperAdminRole = Role::where('name', 'super admin')->first();

            $superAdmin->roles()->attach($getSuperAdminRole);
        }

        $superAdminDefaultPermission = [
            'dashboard' => ['view dashboard'],
            'role' => ['view role', 'create role', 'edit role', 'delete role'],
            'user' => ['view user', 'create user', 'edit user', 'delete user'],
        ];

        $adminPermission = [
            'dashboard' => ['view dashboard'],
            'user' => ['view user', 'create user', 'edit user', 'delete user'],
        ];

        // Merge both arrays
        $mergedPermissions = array_merge_recursive($superAdminDefaultPermission, $adminPermission);

        // Prepare the final array for insertion into the permission table
        $finalPermissions = [];
        foreach ($mergedPermissions as $scope => $permissions) {
            // Remove duplicates
            $uniquePermissions = array_unique($permissions);
            $finalPermissions[$scope] = $uniquePermissions;
        }

        // Insert permissions into the permissions table if not already present
        $superAdmin = User::where('type', 'superadmin')->first();
        foreach ($finalPermissions as $scope => $scopeOfPermission) {
            foreach ($scopeOfPermission as $permission) {
                $permission = Permission::firstOrCreate(
                    ['name' => $permission],
                    [
                        'scope' => $scope,
                        'created_by' => $superAdmin->id,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ],
                );
            }
        }

        // Assign permissions to super admin role
        $superAdminRole = Role::where('name', 'super admin')->first();
        foreach ($superAdminDefaultPermission as $scope => $permissionArr) {
            foreach ($permissionArr as $permissionName) {
                $permission = Permission::where('name', $permissionName)->first();
                if ($permission) {
                    if (!$superAdminRole->hasPermission($permissionName)) {
                        $superAdminRole->givePermission($permission);
                    }
                }
            }
        }

        $admin = User::create([
            'name' => 'Admin',
            'email' => 'admin@example.com',
            'password' => Hash::make('12345678'),
            'type' => 'admin',
            'created_by' => $superAdmin->id,
        ]);

        $role = Role::where('name', 'admin')->first();
        if (empty($role)) {
            $role = Role::create([
                'name' => 'admin',
                'created_by' => $superAdmin->id,
            ]);

            $admin->roles()->attach($role);
        }

        foreach ($adminPermission as $scope => $permissionArray) {
            foreach ($permissionArray as $permissionName) {
                $permission = Permission::where('name', $permissionName)->first();
                if ($permission) {
                    if (!$role->hasPermission($permissionName)) {
                        $role->givePermission($permission);
                    }
                }
            }
        }
    }
}
