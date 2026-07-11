<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Models\Role;

class RoleController extends Controller
{
    public function index()
    {
        if(Auth::user()->can('role.view'))
        {
            $roles = Role::orderBy('id' , 'DESC')->paginate(30);
            return view('users.rolepermissions.roles.index',compact('roles'));
        }
        else
        {
            return redirect('/')->withErrors(__('Doesn\'t have permission to access this resource'));
        }
    }

    public function create()
    {
        if(Auth::user()->can('role.create'))
        {
            return view('users.rolepermissions.roles.create');
        }
        else
        {
            return redirect('/')->withErrors(__('Doesn\'t have permission to access this resource'));
        }
    }// End Method

    public function store(Request $request)
    {
        if(Auth::user()->can('role.create'))
        {
            $request->validate([
                'name' => 'required|max:255|string|unique:roles',
            ]);

            Role::create($request->all());

            return back()->withStatus(__('Role Created Successfully.'));
        }
        else
        {
            return redirect('/')->withErrors(__('Doesn\'t have permission to access this resource'));
        }
    }// End Method

    public function edit($id)
    {
        if(Auth::user()->can('role.edit'))
        {
            $role = Role::where('id' , $id)->first();
            return view('users.rolepermissions.roles.edit',compact('role'));
        }
        else
        {
            return redirect('/')->withErrors(__('Doesn\'t have permission to access this resource'));
        }
    }// End Method

    public function update(Request $request , $id)
    {
        if(Auth::user()->can('role.edit'))
        {
            $request->validate([
                'name' => 'required|max:255|string|unique:roles,name,'.$id,
            ]);
            Role::findOrFail($id)->update($request->all());
            return back()->withStatus(__('Role Updated Successfully.'));
        }
        else
        {
            return redirect('/')->withErrors(__('Doesn\'t have permission to access this resource'));
        }
    }// End Method

    public function delete($id)
    {
        if(Auth::user()->can('role.delete'))
        {
            Role::findOrFail($id)->delete();
            return back()->withStatus(__('Role Deleted Successfully.'));
        }
        else
        {
            return redirect('/')->withErrors(__('Doesn\'t have permission to access this resource'));
        }
    }// End Method

}
