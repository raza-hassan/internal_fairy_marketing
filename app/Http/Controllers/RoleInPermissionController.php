<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Auth;

class RoleInPermissionController extends Controller
{

    public function index()
    {
        if(Auth::user()->can('role-in-permission.view'))
        {
            $roles = Role::paginate(30);
            return view('users.rolepermissions.role_in_permissions.index',compact('roles'));
        }
        else
        {
                return redirect('/')->withErrors(__('Doesn\'t have permission to access this resource'));
        }
    } // End Method

    public function create()
    {
        if(Auth::user()->can('role-in-permission.create'))
        {
            $roles = Role::all();
            $permissions = Permission::all();
            $permission_groups = User::getPermissionGroups(); // coming from user model
            return view('users.rolepermissions.role_in_permissions.create' , compact('roles','permissions','permission_groups'));
        }
        else
        {
            return redirect('/')->withErrors(__('Doesn\'t have permission to access this resource'));
        }
    }// End Method

    public function store(Request $request)
    {
        if(Auth::user()->can('role-in-permission.create'))
        {
            $request->validate([
                'role_id' => 'required|max:255|string|unique:role_has_permissions',
                'permissions'=> 'required',
            ],
            [
                'role_id.required' => 'Please Select The Role',
                'permissions.required' => 'Please Select The Permission For This Role',
            ]);

            $data = array();
            $permissions = $request->permissions;

            foreach($permissions as $permission)
            {
                $data['role_id'] = $request->role_id;
                $data['permission_id'] = $permission;
                DB::table('role_has_permissions')->insert($data);
            }
            return back()->withStatus(__('Role Permission Created Successfully.'));

        }
        else
        {
            return redirect('/')->withErrors(__('Doesn\'t have permission to access this resource'));
        }
    }// End Method

    public function edit($role_id)
    {
        if(Auth::user()->can('role-in-permission.edit'))
        {
            $role = Role::findOrFail($role_id);
            $permissions = Permission::all();
            $permission_groups = User::getpermissionGroups();
            return view('users.rolepermissions.role_in_permissions.edit' , compact('role','permissions','permission_groups'));
        }
        else
        {
            return redirect('/')->withErrors(__('Doesn\'t have permission to access this resource'));
        }
    }// End Method

    public function update(Request $request, $role_id)
    {
        // echo"<pre>"; print_r($request->all()); exit;
        if(Auth::user()->can('role-in-permission.edit'))
        {
            $request->validate([
                'permissions'=> 'required',
            ],[
                'permissions.required' => 'Please Select The Permission For This Role',
            ]);
            $role = Role::findOrFail($role_id);
            $permissions = $request->permissions;
            $data = array();
            foreach ($permissions as $key => $item){
                $data[$key] = (int)$item;
            }
            if (!empty($data)){
                $role->syncPermissions($data);
            }
            return back()->withStatus(__('Role Permission Updated Successfully.'));
        }
        else
        {
            return redirect('/')->withErrors(__('Doesn\'t have permission to access this resource'));
        }
    }// End Method

    public function delete($role_id)
    {
        // echo"<pre>"; print_r($role_id); exit;
        if(Auth::user()->can('role-in-permission.delete'))
        {
            $role = Role::findOrFail($role_id);
            if (!is_null($role_id))
            {
                $role->delete();
            }
            return back()->withStatus(__('Role Permission Deleted Successfully.'));
        }
        else
        {
            return redirect('/')->withErrors(__('Doesn\'t have permission to access this resource'));
        }
    }// End Method
}
