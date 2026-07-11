<?php

namespace App\Http\Controllers\Affiliator;
use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Company;
use App\Models\Product;
use App\Models\Project;

class HomeController extends Controller
{

    public function index()
    {
        $records = Product::orderBy('created_at', 'asc')->paginate(50);
        $products = $records;
        $projects = Project::orderBy('id', 'ASC')->get();
        $categories = Category::orderBy('id', 'ASC')->get();
        $company = Company::orderBy('id', 'ASC')->get();

        return view('freelancer.index', compact( 'company','records', 'projects', 'categories', 'products'));
    }


}
