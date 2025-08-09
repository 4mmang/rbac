<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Models\Permission;
use App\Models\Role;
use App\Models\RolePermission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class PermissionController extends Controller
{
    public function show($id)
    {
        $user = Auth::user();

        $role = Role::with('permissions')->find($id);

        if (!$role || $role->name === 'super admin') {
            abort(404, 'Not Found');
        }

        if ($user->isAbleTo('view role')) {
            $permissions = Permission::all();
            if ($user->type !== 'superadmin') {
                $permissions = Permission::whereNotIn('scope', ['role'])->get();
            }
            $scopedPermissions = Permission::select('scope')->where('scope', '!=', null)->distinct()->get();
            return Inertia::render('Permission/Index', [
                'role' => $role,
                'scopedPermissions' => $scopedPermissions,
                'permissions' => $permissions,
            ]);
        }

        abort(403, 'Unauthorized action.');
    }

    public function update(Request $request)
    {
        $user = Auth::user();
        if ($user->isAbleTo('edit role')) {
            if ($request->all === true) {
                $permissions = Permission::where('scope', $request->scope)->get();
                if ($request->status === true) {
                    foreach ($permissions as $permission) {
                        $rolePermission = RolePermission::where('role_id', $request->role_id)->where('permission_id', $permission->id)->first();
                        if (!$rolePermission) {
                            RolePermission::create([
                                'role_id' => $request->role_id,
                                'permission_id' => $permission->id,
                            ]);
                        }
                    }
                } else {
                    foreach ($permissions as $permission) {
                        $rolePermission = RolePermission::where('role_id', $request->role_id)->where('permission_id', $permission->id)->first();
                        if ($rolePermission) {
                            $rolePermission->delete();
                        }
                    }
                }
            } else {
                $rolePermission = RolePermission::where('role_id', $request->role_id)->where('permission_id', $request->permission_id)->first();

                if ($request->status === true) {
                    if (!$rolePermission) {
                        RolePermission::create([
                            'role_id' => $request->role_id,
                            'permission_id' => $request->permission_id,
                        ]);
                    }
                } else {
                    if ($rolePermission) {
                        $rolePermission->delete();
                    }
                }
            }
            return redirect()->back()->with('success', 'Permission updated successfully.');
        }
        return redirect()->back()->withErrors('error', 'You do not have permission to update permissions.');
    }
}
