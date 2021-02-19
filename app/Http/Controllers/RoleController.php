<?php

namespace App\Http\Controllers;

use App\Role;
use App\Permission;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $roles = Role::all();
        return view('dashboard.users.roles.index', compact('roles'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $permissions = Permission::all();
        return view('dashboard.users.roles.create', compact('permissions'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $role = Role::create([
            'name' => $request->name
        ]);

        $role->attachPermissions($request->permissions);

        session()->flash('success', 'تمت العملية بنجاح');


        return redirect()->route('roles.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Role  $role
     * @return \Illuminate\Http\Response
     */
    public function show(Role $role)
    {
        return view('dashboard.users.roles.show', compact('role'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Role  $role
     * @return \Illuminate\Http\Response
     */
    public function edit(Role $role)
    {
        $permissions = Permission::all();
        // find the permissions of user
        $role_permissions = [];
        $role_permissions = json_encode(array_column($role->permissions->toArray(), 'id'));
        return view('dashboard.users.roles.edit', compact('permissions', 'role_permissions', 'role'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Role  $role
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Role $role)
    {
        if($role->isSuper()){
            return back()->with('error', 'لا يمكنك تعديل هذا الدور');
        }
        $role->update([
            'name' => $request->name
        ]);

        $role->permissions()->sync($request->permissions);

        session()->flash('success', 'تمت العملية بنجاح');


        return redirect()->route('roles.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Role  $role
     * @return \Illuminate\Http\Response
     */
    public function destroy(Role $role)
    {
        if($role->isDefault()){
            return back()->with('error', 'لا يمكنك حذف دور إفتراضي');
        }
        $role->delete();

        session()->flash('success', 'تمت العملية بنجاح');

        return redirect()->route('roles.index');

    }
}
