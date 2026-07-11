<?php

namespace App\Http\Controllers\admin;
use App\Http\Controllers\Controller;

use App\Models\Company;
use App\Models\Offices;
use App\Models\Project;
use Illuminate\Http\Request;

class CompanyController extends Controller
{

    public function index()
    {
        $companies=Company::all();
        return view('admin.companies.index' , compact('companies'));
    }


    public function create()
    {
        // echo 'check';exit;
        return view('admin.companies.create');
    }

    public function store(Request $request)
    {
        // echo "<pre>";print_r($request->all()); exit;
        Company::create($request->all());
        return redirect('admin/company')->withStatus(__('Company Created Successfully.'));

    }

    public function show(Company $company)
    {
        //
    }

    public function edit(Company $company)
    {
        //   echo 'check';exit;
        return view('admin.companies.edit', compact('company'));
    }

    public function update(Request $request, Company $company)
    {
        $company->update($request->all());
        return redirect('admin/company')->withStatus(__('Company Updated Successfully.'));
    }

    public function destroy(Company $company)
    {
        $company->delete();
        return redirect('admin/company')->withStatus(__('Company Deleted Successfully.'));
    }
}
