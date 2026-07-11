<?php

namespace App\Http\Controllers\outer;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use App\Models\Tag;
use App\Models\Related;
use App\Models\Productmeta;
use App\Models\Variation;
use App\Models\Attributes;
use App\Models\Company;
use App\Models\Project;
use App\Models\LeadNotes;
use Illuminate\Http\Request;
use Image;
use Auth;
use Illuminate\Support\Str;

class ProductController extends Controller {

    public function index() {
        $records = Product::orderBy('created_at', 'asc')->paginate(50);
        $products = $records;
        $projects = Project::orderBy('id', 'ASC')->get();
        $categories = Category::orderBy('id', 'ASC')->get();
        $company = Company::orderBy('id', 'ASC')->get();

        return view('outer.products.index', compact( 'company','records', 'projects', 'categories', 'products'));
    }

    public function show() {

        if (Auth::user()->role == 5) {
            $records = Product::where('hold_by', '>', 0)->orderBy('created_at', 'asc')->paginate(50);
        } else {
            $records = Product::where('hold_by', Auth::user()->id)->orderBy('created_at', 'asc')->paginate(50);
        }
        $products = $records;
        $projects = Project::orderBy('id', 'ASC')->get();
        $categories = Category::orderBy('id', 'ASC')->get();
        return view('outer.products.index', compact('records', 'projects', 'categories', 'products'));
    }

    public function search(Request $request) {

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
        $clients = array();
        if ($request->input('name') || $request->input('unit_id') != '' || $request->input('project_id') != 0 || $request->input('category_id') != 0 || $request->input('floor') != '') {
            $records = Product::where($condition)->orderBy('id', 'desc')->paginate(30);
        } else {
            $records = Product::orderBy('id', 'desc')->paginate(30);
        }

        $projects = Project::orderBy('id', 'ASC')->get();
        $categories = Category::orderBy('id', 'ASC')->get();
        $products = Product::orderBy('created_at', 'desc')->get();
        return view('outer.products.index', compact('records', 'projects', 'categories', 'products'));
    }

    public function existence(Request $request)
    {

        if ($request->date == '' || $request->note == '')
        {
            return redirect()->back()->withErrors(__('Please Fill All The Fields'));
        }
        LeadNotes::insert([
            'notes' => $request->note,
            'status' => 1,
            'lead_id' => 0,
            'item_id' => $request->product_id,
            'hold_by' => $request->hold_by,
        ]);

        $date = date("Y-m-d", strtotime($request->date)).' '.date('H:i:s');
        Product::where('id', $request->product_id)
                ->update([
                    'hold_by' => $request->hold_by,
                    'hold_expiary' => $date,
                    'hold_status' => 1,
                    'status' =>  $request->status,
                    'sold_by' =>  $request->status.' By '.$request->company,
        ]);
        return redirect('outer/inventory')->withStatus(__('Submitted  Successfully.'));
    }

}
