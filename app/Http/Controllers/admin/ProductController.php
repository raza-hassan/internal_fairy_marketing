<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;

use App\Models\Product;

use App\Models\Category;

use App\Models\Tag;

use App\Models\Related;

use App\Models\Productmeta;

use App\Models\Variation;

use App\Models\Attributes;

use App\Models\Project;

use Illuminate\Http\Request;

use Image;

use Carbon\Carbon;

use Illuminate\Support\Str;

class ProductController extends Controller {


    public function index() {

        $records = Product::orderBy('created_at', 'desc')->paginate(50);

        $products = $records;

        $projects = Project::orderBy('id', 'ASC')->get();

        $categories = Category::orderBy('id', 'ASC')->get();

        return view('admin.products.index', compact('records', 'projects', 'categories', 'products'));

    }


    public function create() {

        $categories = Category::orderby('name', 'asc')->get();

        $projects = Project::orderBy('id', 'ASC')->get();

        return view('admin.products.create', compact('categories', 'projects'));
    }

    public function store(Request $request) {

        $validated = $request->validate([

            'name' => 'required',

            'price' => 'required',

            'sku' => 'required|unique:product',
            'discount'=> 'nullable|numeric',


        ]);

        $product = Product::create($request->merge(['category_id' => $request->input('categories')])->all());

        if ($request->input('categories') != '0') {

            $category = Category::find($request->input('categories'));

            $product->categories()->attach($category);

        }

        return redirect('admin/inventory')->withStatus(__('Products successfully added.'));

    }

    public function createThumbnail($path, $width, $height) {

        $img = Image::make($path)->resize($width, $height, function ($constraint) {

            $constraint->aspectRatio();

        });

        $img->save($path);

    }

    public function edit(Product $product) {

        $products = Product::all();

        $projects = Project::orderBy('id', 'ASC')->get();

        $categories = Category::orderby('name', 'asc')->get();

        return view('admin.products.edit', compact('product', 'categories', 'projects'));

    }

    public function update(Request $request, Product $product) {

        $validated = $request->validate([
            'name' => 'required',
            'price' => 'required',
            'sku' => 'required',
            'discount'=> 'nullable|numeric',

        ]);

        if($request->discount != ''){
            $discount = $request->discount;
        }else{
            $discount = 0;
        }

        // $product->update($request->all());
        $product->update($request->merge(['discount' => $discount])->all());

        $category = Category::find($request->input('categories'));

        $product->categories()->sync($category);

        return redirect('admin/inventory')->withStatus(__('Products successfully updated.'));

    }

    public function destroy(Product $product) {

        $product->delete();

        return back()->withStatus(__('Products successfully deleted.'));

    }

    public function checkstatus() {

        $products = Product::where('status', 'Hold')->where('hold_status', 1)->whereDate('hold_expiary', '<=', Carbon::now()->timezone('Asia/Karachi'))->get();

        foreach ($products as $product) {

            $product->hold_status = 0;

            $product->status = 'Available';

            $product->save();

        }
    }

    public function import() {
        return view('admin.products.import');

    }

    public function search(Request $request) {

        // Get the search value from the request

        $condition = array();

        if ($request->input('unit_id') != 0) {

            $condition[] = array('id', '=', $request->input('unit_id'));

        }

        if ($request->input('status') != 0) {

            $condition[] = array('status', '=', $request->input('status'));

        }

        if ($request->input('project_id') != 0) {

            $condition[] = array('project_id', '=', $request->input('project_id'));

        }

        if ($request->input('category_id') != 0) {

            $condition[] = array('category_id', '=', $request->input('category_id'));

        }

        $records = Product::where($condition)

                ->paginate(50);

        $products = Product::all();

        $projects = Project::orderBy('id', 'ASC')->get();

        $categories = Category::orderby('name', 'asc')->get();

        return view('admin.products.index', compact('records', 'projects', 'categories', 'products'));

    }

}

