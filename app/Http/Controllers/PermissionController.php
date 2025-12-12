<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;
use App\Helpers\Qs;
use App\Http\Controllers\Controller;

class PermissionController extends Controller
{
    public function __construct()
    {
        $this->middleware('teamSA');
        $this->middleware('can:permissions.manage');
    }

    public function index()
    {
        $permissions = Permission::all();
        return view('pages.permission.index', compact('permissions'));
    }

    public function create()
    {
        return view('pages.permission.create');
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|unique:permissions,name',
        ]);

        Permission::create(['name' => $request->name, 'guard_name' => 'web']);
        return redirect()->route('permissions.index')->with('success', 'Permission created successfully!');
    }

    public function edit($id)
    {
        $permission = Permission::findById($id);
        return view('pages.permission.edit', compact('permission'));
    }

    public function update(Request $request, $id)
    {
        $permission = Permission::findById($id);
        $this->validate($request, [
            'name' => 'required|unique:permissions,name,' . $permission->id,
        ]);
        
        $permission->update(['name' => $request->name]);
        return redirect()->route('permissions.index')->with('success', 'Permission updated successfully!');
    }

    public function destroy($id)
    {
        $permission = Permission::findById(Qs::decodeHash($id));
        $permission->delete();
        return redirect()->route('permissions.index')->with('success', 'Permission deleted successfully!');
    }
}
