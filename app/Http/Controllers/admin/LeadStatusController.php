<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\LeadStatus;
use Illuminate\Http\Request;

class LeadStatusController extends Controller
{

    public function index()
    {
        $leadstatus = LeadStatus::orderBy('id', 'DESC')->get();

        return view('admin.leadstatus.index', compact('leadstatus'));
    }


    public function create()
    {
        return view('admin.leadstatus.create');
    }


        public function store(Request $request)
        {
            $validated = $request->validate([
                'type' => 'required',
            ]);

            LeadStatus::create($request->all());
            return redirect('admin/status')->withStatus(__('Lead Status Created Successfully.'));
        }


        public function edit(LeadStatus $status)
        {
            return view('admin.leadstatus.edit', compact('status'));
        }



        public function update(Request $request, LeadStatus $status)
        {
            $status->update($request->all());
            return redirect('admin/status')->withStatus(__('Lead Status Updated Successfully.'));
        }


        public function destroy(LeadStatus $status) {
            $status->delete();

             return back()->withStatus(__('Lead Status Successfully Deleted.'));
         }



}
