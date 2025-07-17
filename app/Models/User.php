<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    // use HasApiTokens;
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function roles()
    {
        return $this->belongsToMany(Role::class, 'role_user', 'user_id', 'role_id');
    }

    public function makeRoles()
    {
        // Manager Role
        // $managerDefaultPermissions = [
        //     'dashboard' => ['view dashboard'],
        //     'user' => ['manage user', 'create user', 'edit user', 'delete user'],
        // ];
        // $managerRole = Role::where('name', 'manager')->where('created_by', $this->id)->first();        
        // if (empty($managerRole)) {
        //     $managerRole = Role::create([
        //         'name' => 'manager',
        //         'created_by' => $this->id
        //     ]);
        // }
        // foreach ($managerDefaultPermissions as $scope => $permissionArr) {
        //     foreach ($permissionArr as $permissionName) {
        //         $checkPermission = Permission::where('name', $permissionName)->first();
        //         if ($checkPermission) {
        //             if (!$managerRole->hasPermission($permissionName)) {
        //                 $managerRole->givePermission($checkPermission);
        //             }
        //         } else {
        //             $permission = Permission::firstOrCreate(
        //                 ['name' => $permissionName],
        //                 [
        //                     'scope' => $scope,
        //                     'created_by' => $this->id,
        //                     'created_at' => now(),
        //                     'updated_at' => now()
        //                 ]
        //             );
        //             $checkPermission = Permission::where('name', $permissionName)->first();
        //             if ($checkPermission) {
        //                 if (!$managerRole->hasPermission($permissionName)) {
        //                     $managerRole->givePermission($checkPermission);
        //                 }
        //             }
        //         }
        //     }
        // }

        // $adminDefaultPermissions = [
        //     'dashboard' => ['view dashboard'],
        // ];

        // // employee Role
        // $employeeRole = Role::where('name', 'employee')->where('created_by', $this->id)->first();
        // if (empty($employeeRole)) {
        //     $employeeRole = Role::create([
        //         'name' => 'employee',
        //         'created_by' => $this->id
        //     ]);
        // }
        // foreach ($adminDefaultPermissions as $scope => $permissionArr) {
        //     foreach ($permissionArr as $permissionName) {
        //         $checkPermission = Permission::where('name', $permissionName)->first();
        //         if ($checkPermission) {
        //             if (!$employeeRole->hasPermission($permissionName)) {
        //                 $employeeRole->givePermission($checkPermission);
        //             }
        //         } else {
        //             $permission = Permission::firstOrCreate(
        //                 ['name' => $permissionName],
        //                 [
        //                     'scope' => $scope,
        //                     'created_by' => $this->id,
        //                     'created_at' => now(),
        //                     'updated_at' => now()
        //                 ]
        //             );
        //             $checkPermission = Permission::where('name', $permissionName)->first();
        //             if ($checkPermission) {
        //                 if (!$employeeRole->hasPermission($permissionName)) {
        //                     $employeeRole->givePermission($checkPermission);
        //                 }
        //             }
        //         }
        //     }
        // }
    }

    public function isAbleTo($permission)
    {
        return $this->hasPermission($permission);
    }

    public function hasPermission($permission)
    {
        foreach ($this->roles as $role) {
            if ($role->permissions->contains('name', $permission)) {
                return true;
            }
        }
        return false;
    }

    public function hasRole($roleName)
    {
        return $this->roles->contains('name', $roleName);
    }
}
