<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RoleController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        if ($user->isAbleTo('view role') && $user->type === 'superadmin') {
            $roles = Role::where('name', '!=', 'super admin')->get();
            return inertia('Role/Index', [
                'roles' => $roles,
            ]);
        }
    }

    public function store(Request $request)
    {
        $user = Auth::user();
        if ($user->isAbleTo('create role') && $user->type === 'superadmin') {
            $request->validate([
                'name' => 'required|string|max:255|unique:roles,name',
            ]);

            Role::create([
                'name' => strtolower($request->name),
                'created_by' => $user->id,
            ]);

            return redirect()->back()->with('success', 'Role created successfully.');
        }

        return redirect()->back()->with('error', 'You do not have permission to create roles.');
    }

    public function update(Request $request, Role $role)
    {
        $user = Auth::user();
        if ($user->isAbleTo('edit role') && $user->type === 'superadmin') {
            $request->validate([
                'name' => 'required|string|max:255|unique:roles,name,' . $role->id,
            ]);

            $role->update([
                'name' => strtolower($request->name),
                'updated_by' => $user->id,
            ]);

            return redirect()->back()->with('success', 'Role updated successfully.');
        }

        return redirect()->back()->with('error', 'You do not have permission to edit roles.');
    }
}
