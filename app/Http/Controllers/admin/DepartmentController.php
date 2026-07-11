<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Departments;
use Illuminate\Http\Request;

class DepartmentController extends Controller
{

    public function index()
    {
        $department = Departments::orderBy('id', 'DESC')->get();

        return view('admin.departments.index', compact('department'));
    }



    public function create()
    {
        return view('admin.departments.create');
    }


    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required',
        ]);

        Departments::create($request->all());
        return redirect('admin/departments')->withStatus(__('Department Created Successfully.'));
    }


    public function edit(Departments $departments)
    {
        return view('admin.departments.edit', compact('departments'));
    }


    public function update(Request $request, Departments $departments)
    {
        $departments->update($request->all());
        return redirect('admin/departments')->withStatus(__('Department Updated Successfully.'));
    }


    public function destroy(Departments $departments)
    {
        $departments->delete();
        return back()->withStatus(__('Department Successfully Deleted.'));
    }




}
