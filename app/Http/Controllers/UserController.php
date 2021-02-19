<?php

namespace App\Http\Controllers;

use App\User;
use App\Employee;
use App\Role;
use App\Permission;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class UserController extends Controller
{

    public function __construct()
    {
        $this->middleware('permission:users-create')->only(['create', 'store']);
        $this->middleware('permission:users-read')->only(['index', 'show']);
        $this->middleware('permission:users-update')->only(['edit', 'update']);
        $this->middleware('permission:users-delete')->only('destroy');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $users = User::where('id', '!=', auth()->user()->id)->get();
        return view('dashboard.users.index',compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $employees = Employee::all();
        $permissions = Permission::all();
        $roles = Role::all();
        return view('dashboard.users.create',compact('employees', 'permissions', 'roles'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        request()->validate([
            'username'      => 'required | string | max:100 | min:3 |regex:/^[A-Za-z]+$/| unique:users',
            'password'      => 'required | string | min:6',
            'employee_id'   => 'required | string',
        ]);
        
        $request_data = $request->except('password');
        $request_data['password'] = bcrypt($request->password);
        $user = User::create($request_data);

        
        $user->roles()->attach($request->roles);

        $user->permissions()->attach($request->permissions);
        
        session()->flash('success', 'تمت العملية بنجاح');


        if($request->next == 'back') {
            return back();
        }else {
            return redirect()->route('users.index');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        return view('dashboard.users.show', compact('user'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function edit(User $user)
    {
        $employees = Employee::all();
        $permissions = Permission::all();
        $roles = Role::all();

        // find the permissions of user
        $user_permissions = [];
        $user_permissions = json_encode(array_column($user->permissions->toArray(), 'id'));

        
        return view('dashboard.users.edit', compact('user', 'employees', 'permissions', 'roles', 'user_permissions'));
        
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user)
    {
        request()->validate([
            'username'      => 'required | string | max:100 | min:3|regex:/^[A-Za-z]+$/',
            'password'      => 'nullable | string | min:6',
        ]);
    
        $request_data = $request->except('password');

        if($request->password) {
            $request_data['password'] = bcrypt($request->password);
        }

        $user->update($request_data);

        $user->roles()->sync($request->roles);

        $user->permissions()->sync($request->permissions);
        
        session()->flash('success', 'تمت العملية بنجاح');


        if($request->next == 'back') {
            return back();
        }else {
            return redirect()->route('users.index');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        if(count($user->bills)) {
            session()->flash('error', 'لا يمكن حذف المستخدم');
            return redirect()->route('users.index');
        }else {
            $user->roles()->detach();
    
            $user->permissions()->detach();
    
            $user->delete();
    
            session()->flash('success', 'تمت العملية بنجاح');
    
    
            return redirect()->route('users.index');
        }

    }


    public function profile(Request $request) {

        $user = User::find(auth()->user()->id);

        request()->validate([
            'username'      => 'required | string | max:100 | min:3|regex:/^[A-Za-z]+$/',
            'password'      => 'nullable | string | min:6   | confirmed',
        ]);
    
        $request_data = $request->except('password');

        if($request->password) {
            $request_data['password'] = bcrypt($request->password);
        }

        if(\Hash::check($request->old_password, $user->password) && $request->old_password != $request->password  ) {
            $user->update($request_data);
            session()->flash('success', 'تمت العملية بنجاح');
        }else {
            if($request->old_password == $request->password) {
                session()->flash('error', 'يجب ادخال كلمة مرور جديدة');
            }else {
                session()->flash('error', 'كلمة المرور الحالية عير صحيحة');
            }
        }

        
        return back();
    }
}
