<?php

namespace App\Http\Controllers;
use App\Http\Controllers\Controller;

use App\Models\Compain;
use App\Models\Offices;
use App\Models\Project;
use Illuminate\Http\Request;

class CompainController extends Controller
{

    public function index() {

        $compains=Compain::all();
        return view('compain.index' , compact('compains'));
    }

    public function create()
    {
        // echo 'check';exit;
        $offices = Offices::all();
        $projects = Project::all();
        return view('compain.create', compact('offices' , 'projects'));
    }

    public function store(Request $request)
    {
        // echo "<pre>";print_r($request->all()); exit;
        Compain::create($request->all());
        return redirect('compain')->withStatus(__('Compain Created Successfully.'));
    }

    public function show(compain $compain)
    {
        //
    }

    public function edit(compain $compain)
    {
        //   echo 'check';exit;
        $offices = Offices::all();
        $projects = Project::all();
        return view('compain.edit', compact('compain','offices' , 'projects'));
    }

    public function update(Request $request, compain $compain)
    {
        $compain->update($request->all());
        return redirect('compain')->withStatus(__('Compain Updated Successfully.'));
    }

    // public function destroy(compain $compain)
    // {
    //     $compain->delete();
    //     return redirect('admin/compain')->withStatus(__('Compain Deleted Successfully.'));
    // }


    public function compainInActive($id)
    {
        Compain::findOrFail($id)->update([ 'status' => 0  ]);
        return redirect()->back()->withStatus(__('Compain In-Active Successfully.'));
    }

    public function compainActive($id)
    {
        Compain::findOrFail($id)->update([ 'status' => 1  ]);
        return redirect()->back()->withStatus(__('Compain Active Successfully.'));
    }


}
