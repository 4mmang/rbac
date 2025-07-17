<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DefaultUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // $superAdmin = User::where('type', 'superadmin')->first();
        // $admin = User::where('type', 'admin')->first();

        // if (empty($admin)) {
        //     $admin = new User();
        //     $admin->name = 'admin';
        //     $admin->email = 'admin@example.com';
        //     $admin->password = Hash::make('12345678');
        //     $admin->type = 'admin';
        //     $admin->created_by = $superAdmin->id;
        //     $admin->save();

        //     $adminRole = Role::where('name', 'admin')->first();
        //     $admin->roles()->attach($adminRole);

        //     $admin->makeRoles();
        // } else {
        //     $admin->makeRoles();
        // } 
    }
}
