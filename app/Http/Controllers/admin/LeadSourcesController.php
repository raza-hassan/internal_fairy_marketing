<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\LeadSource;
use Illuminate\Http\Request;

class LeadSourcesController extends Controller
{

    public function index()
    {
        $leadsource = LeadSource::orderBy('id', 'DESC')->get();

        return view('admin.leadsource.index', compact('leadsource'));
    }



    public function create()
    {
        return view('admin.leadsource.create');
    }


    public function store(Request $request)
    {
        $validated = $request->validate([
            'type' => 'required',
            'subtype' => 'required',
        ]);

        LeadSource::create($request->all());
        return redirect('admin/sources')->withStatus(__('Lead_Sources Created Successfully.'));
    }


    public function edit(LeadSource $sources)
    {
        return view('admin.leadsource.edit', compact('sources'));
    }


    public function update(Request $request, LeadSource $sources)
    {
        $sources->update($request->all());
        return redirect('admin/priority')->withStatus(__('Lead Sources Updated Successfully.'));
    }


    public function destroy(LeadSource $sources)
    {
        $sources->delete();
        return back()->withStatus(__('Lead Sources Successfully Deleted.'));
    }





}
