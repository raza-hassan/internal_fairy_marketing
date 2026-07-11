<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Auth;

class PermissionController extends Controller
{
    public function index()
    {
        if(Auth::user()->can('permission.view'))
        {
            $permissions = Permission::orderBy('id' , 'DESC')->paginate(30);
            return view('users.rolepermissions.permissions.index',compact('permissions'));
        }
        else
        {
            return redirect('/')->withErrors(__('Doesn\'t have permission to access this resource'));
        }
    } // End Method

    public function create()
    {
        if(Auth::user()->can('permission.create'))
        {
            return view('users.rolepermissions.permissions.create');
        }
        else
        {
            return redirect('/')->withErrors(__('Doesn\'t have permission to access this resource'));
        }
    }// End Method

    public function store(Request $request)
    {
        if(Auth::user()->can('permission.create'))
        {
            $request->validate([
                'name' => 'required|max:255|string|unique:permissions',
                'group_name' => 'required|max:255|string',
            ]);

            Permission::create(
            [
                'name' => $request->name,
                'group_name' => $request->group_name,
                'created_at' => Carbon::now(),
            ]);

            return back()->withStatus(__('Permission Created Successfully.'));
        }
        else
        {
                return redirect('/')->withErrors(__('Doesn\'t have permission to access this resource'));
        }
    }// End Method

    public function edit($id)
    {
        if(Auth::user()->can('permission.edit'))
        {
            $permission = Permission::where('id' , $id)->first();
            return view('users.rolepermissions.permissions.edit',compact('permission'));
        }
        else
        {
            return redirect('/')->withErrors(__('Doesn\'t have permission to access this resource'));
        }
    }// End Method

    public function update(Request $request , $id)
    {
        if(Auth::user()->can('permission.edit'))
        {
            $request->validate([
                'name' => 'required|max:255|string|unique:permissions,name,'.$id,
            ]);
            Permission::findOrFail($id)->update([
                'name' => $request->name,
                'group_name' => $request->group_name,
                'updated_at' => Carbon::now(),
            ]);
            return back()->withStatus(__('Permission Updated Successfully.'));
        }
        else
        {
            return redirect('/')->withErrors(__('Doesn\'t have permission to access this resource'));
        }
    }// End Method

    public function delete($id)
    {
        if(Auth::user()->can('permission.delete'))
        {
            Permission::findOrFail($id)->delete();
            return back()->withStatus(__('Permission Deleted Successfully.'));
        }
        else
        {
            return redirect('/')->withErrors(__('Doesn\'t have permission to access this resource'));
        }
    }// End Method

}
