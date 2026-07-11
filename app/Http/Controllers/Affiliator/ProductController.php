<?php

namespace App\Http\Controllers\Affiliator;
use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Compain;
use App\Models\Company;
use App\Models\Offices;
use App\Models\Product;
use App\Models\Project;
use Illuminate\Http\Request;

class ProductController extends Controller
{

    public function index()
    {
        $records = Product::where('status' , '=' , 'Available')->orderBy('created_at', 'asc')->paginate(50);
        $products = $records;
        $projects = Project::orderBy('id', 'ASC')->get();
        $categories = Category::orderBy('id', 'ASC')->get();
        $company = Company::orderBy('id', 'ASC')->get();
        return view('freelancer.products.index', compact('records', 'projects', 'categories','products' , 'company'));

    }

    public function create()
    {
        // $offices = Offices::all();
        // $projects = Project::all();
        // return view('compain.create', compact('offices' , 'projects'));
    }

    public function store(Request $request)
    {
        // Compain::create($request->all());
        // return redirect('compain')->withStatus(__('Compain Created Successfully.'));
    }

    public function show(compain $compain)
    {
        //
    }

    public function edit(compain $compain)
    {
        // $offices = Offices::all();
        // $projects = Project::all();
        // return view('compain.edit', compact('compain','offices' , 'projects'));
    }

    public function update(Request $request, compain $compain)
    {
        // $compain->update($request->all());
        // return redirect('compain')->withStatus(__('Compain Updated Successfully.'));
    }

    public function destroy(compain $compain)
    {
        // $compain->delete();
        // return redirect('admin/compain')->withStatus(__('Compain Deleted Successfully.'));
    }

    public function search(Request $request) {
        // Get the search value from the request
        $condition = array();

        if ($request->input('floor') > 0) {
            $condition[] = array('floor', $request->input('floor'));
        }

        if ($request->input('unit_id') != '') {
            $condition[] = array('unitid', $request->input('unit_id'));
        }

        if ($request->input('building') > 0) {
            $condition[] = array('building', $request->input('building'));
        }

        if ($request->input('type') > 0) {
            $condition[] = array('type', $request->input('type'));
        }

        if ($request->input('subtype') > 0) {
            $condition[] = array('subtype', $request->input('subtype'));
        }

        if ($request->input('project_id') > 0) {
            $condition[] = array('project_id', $request->input('project_id'));
        }

        if ($request->input('category_id') > 0) {
            $condition[] = array('category_id', $request->input('category_id'));
        }
        if ($request->input('status') > 0) {
            $condition[] = array('status', $request->input('status'));
        }

        // Search in the title and body columns from the posts table
        $clients = array();
        if ($request->input('name') || $request->input('unit_id') != '' || $request->input('project_id') != 0 || $request->input('category_id') != 0 || $request->input('floor') != '') {
            $records = Product::where($condition)->where('status' , '=' , 'Available')->orderBy('id', 'desc')->paginate(30);
        }else{
            $records = Product::where('status' , '=' , 'Available')->orderBy('id', 'desc')->paginate(30);
        }


        $projects = Project::orderBy('id', 'ASC')->get();
        $categories = Category::orderBy('id', 'ASC')->get();
        $products = Product::orderBy('created_at', 'desc')->get();
        $company = Company::orderBy('id', 'ASC')->get();
        return view('freelancer.products.index', compact('records', 'projects', 'categories','products','company'));

    }


}
