<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\LeadPriority;
use Illuminate\Http\Request;

class LeadPriorityController extends Controller
{


    public function index()
    {
        $leadpriority = LeadPriority::orderBy('id', 'DESC')->get();

        return view('admin.leadpriority.index', compact('leadpriority'));
    }


    public function create()
    {
        return view('admin.leadpriority.create');
    }


    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required',
        ]);

        LeadPriority::create($request->all());
        return redirect('admin/priority')->withStatus(__('Lead_Priority Created Successfully.'));
    }


    public function edit(LeadPriority $priority)
    {
        return view('admin.leadpriority.edit', compact('priority'));
    }


    public function update(Request $request, LeadPriority $priority)
    {
        $priority->update($request->all());
        return redirect('admin/priority')->withStatus(__('Lead Priority Updated Successfully.'));
    }


    public function destroy(LeadPriority $priority)
    {
        $priority->delete();
        return back()->withStatus(__('Lead Priority Successfully Deleted.'));
    }




}
