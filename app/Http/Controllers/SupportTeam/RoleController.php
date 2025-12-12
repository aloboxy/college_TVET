<?php

namespace App\Http\Controllers\SupportTeam;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Helpers\Qs;

class RoleController extends Controller
{
    public function __construct()
    {
        $this->middleware('teamSA');
    }

    public function index()
    {
        $d['roles'] = Role::all();
        return view('pages.support_team.roles.index', $d);
    }

    public function create()
    {
        return view('pages.support_team.roles.create');
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|unique:roles,name',
        ]);

        Role::create(['name' => $request->name]);
        return redirect()->route('roles.index')->with('success', 'Role created successfully');
    }

    public function edit($id)
    {
        $d['role'] = Role::findById(Qs::decodeHash($id));
        $d['permissions'] = Permission::all();
        return view('pages.support_team.roles.edit', $d);
    }

    public function update(Request $request, $id)
    {
        $role = Role::findById(Qs::decodeHash($id));
        $this->validate($request, [
            'name' => 'required|unique:roles,name,' . $role->id,
        ]);

        $role->update(['name' => $request->name]);

        if($request->has('permissions')){
            $role->syncPermissions($request->permissions);
        }

        return redirect()->route('roles.index')->with('success', 'Role updated successfully');
    }

    public function destroy($id)
    {
        $role = Role::findById(Qs::decodeHash($id));
        $role->delete();
        return back()->with('success', 'Role deleted successfully');
    }
}
