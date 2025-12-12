<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Permission;
use App\User;
Use App\Helpers\Qs;

class UserPermissionController extends Controller
{
    //
    public function show($user)
    {
        $user = Qs::decodeHash($user);
        $user = User::where('id', $user)->first();
        $groupedPermissions = Permission::all()->groupBy('category');
        return view('pages.permission.assign', compact('user', 'groupedPermissions'));
    }

    // Handle permission assignment
    public function assign(Request $request, $user)
    {
        $user = User::where('id', $user)->first();
        $user->permissions()->sync($request->permissions);
        return redirect()->back()->with('success', 'Permissions updated successfully!');
    }
}
