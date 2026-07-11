<?php
namespace App\Http\Controllers;
use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use App\Models\Company;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Helpers\Helper;
use App\Models\Discount;
use Carbon\Carbon;
use App\Models\LeadNotes;
use App\Models\Order;
use App\Models\ProductNotes;
use App\Models\User;

class ProductController extends Controller
{
    public function checkstatus() {
        $products = Product::where('status', 'Hold')->where('hold_status', 1)->whereDate('hold_expiary', '<=', Carbon::now()->timezone('Asia/Karachi'))->get();

        foreach ($products as $product) {
            $product->hold_status = 0;
            $product->status = 'Available';
            $product->save();
        }
    }

    public function index()
    {
        if(Auth::user()->can('inventory.view'))
        {
            if(Auth::user()->role==11 || Auth::user()->role==12 ){
                $records = Product::where('status', 'Available')->orderBy('created_at', 'asc')->paginate(50);
            }else{
                $records = Product::orderBy('created_at', 'asc')->paginate(50);
            }
            $products = $records;
            $projects = Project::orderBy('id', 'ASC')->get();
            $categories = Category::orderBy('id', 'ASC')->get();
            $company = Company::orderBy('id', 'ASC')->get();
            return view('products.index', compact('records', 'projects', 'categories','products' , 'company' ));
        }else{
            return redirect('/')->withErrors(__('Doesn\'t have permission to access this resource'));
        }
    }

    public function edit(Product $product)
    {
        if(Auth::user()->can('inventory.edit'))
        {
            $products = Product::all();
            $projects = Project::orderBy('id', 'ASC')->get();
            $categories = Category::orderby('name', 'asc')->get();
            return view('products.edit', compact('product', 'categories', 'projects'));
        }
        else{
            return redirect('/')->withErrors(__('Doesn\'t have permission to access this resource'));
        }
    }

    public function update(Request $request, Product $product)
    {
        if(Auth::user()->can('inventory.edit'))
        {
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
            return redirect('inventory')->withStatus(__('Product successfully updated.'));
        }
        else{
            return redirect('/')->withErrors(__('Doesn\'t have permission to access this resource'));
        }
    }

    public function show()
    {
        if(Auth::user()->can('inventory.prices.update'))
        {
            $projects = Project::orderBy('id', 'ASC')->get();
            $categories = Category::orderBy('id', 'ASC')->get();
            $buildings =Product::select('building')->where('building' , '!=' , '')->groupBy('building')->get();
            $types     =Product::select('type')->where('type' , '!=' , '')->groupBy('type')->get();
            $subtypes  =Product::select('subtype')->where('subtype' , '!=' , '')->groupBy('subtype')->get();
            $records = Product::where('unitid', '0')->paginate(50);
            return view('products.price', compact('projects', 'categories' , 'records' ,'buildings' , 'types' , 'subtypes'));    
        }
        else{
            return redirect('/')->withErrors(__('Doesn\'t have permission to access this resource'));
        }
    }

    // public function loadTypes(Request $request)
    // {
    //     $building_name = $request->building_name;
    //     $html = '';
    //     $types   =Product::select('type')->where('building' , 'like' , $building_name)->where('type' , '!=' , '')->groupBy('type')->get();

    //     if(!empty($types) && count($types) > 0)
    //     {
    //         $html .='<div class="bootstrap-select fm-cmp-mg" id="types_load">
    //                     <select class="form-control" name="type" required="required">';
    //                         $html .= '<option value="0">All</option>';
    //                         foreach($types as $product)
    //                         {
    //                             $html .= '<option value="'.$product->type.'"> ' . $product->type . ' </option>';
    //                         }
    //             $html .= '</select></div>';
    //     }else{
    //         $types =Product::select('type')->where('type' , '!=' , '')->groupBy('type')->get();

    //         $html .='<div class="bootstrap-select fm-cmp-mg" id="types_load">
    //                     <select class="form-control" name="type" required="required">';
    //                         $html .= '<option value="0">All</option>';
    //                         foreach($types as $product)
    //                         {
    //                             $html .= '<option value="'.$product->type.'"> ' . $product->type . ' </option>';
    //                         }
    //             $html .= '</select></div>';
    //     }
    //     return response()->json(['html' => $html]);
    // } // End Method

    public function loadTypes(Request $request)
    {
        // Retrieve the building name from the request
        $building_name = $request->building_name;

        // Initialize the HTML variable for storing the select dropdown
        $html = '';

        // Query to get the product types based on the building name
        $typesQuery = Product::select('type')
            ->where('type', '!=', '') // Exclude empty types
            ->groupBy('type'); // Group by type to get distinct values

        // If a building name is provided, apply the filter for building name
        if ($building_name) {
            $typesQuery->where('building', 'like', $building_name);
        }

        // Execute the query
        $types = $typesQuery->get();

        // Check if types were found, if so, generate the HTML for the dropdown
        if (!$types->isEmpty()) {
            $html .= '<div class="bootstrap-select fm-cmp-mg" id="types_load">
                        <select class="form-control" name="type" required="required">';
            $html .= '<option value="0">All</option>';

            // Loop through the types and create the dropdown options
            foreach ($types as $product) {
                $html .= '<option value="' . $product->type . '">' . $product->type . '</option>';
            }

            $html .= '</select></div>';
        } else {
            // If no types are found, you can either return an empty select or a custom message
            // Here we generate a fallback select dropdown with a default "All" option
            $html .= '<div class="bootstrap-select fm-cmp-mg" id="types_load">
                        <select class="form-control" name="type" required="required">';
            $html .= '<option value="0">No types available</option>';
            $html .= '</select></div>';
        }

        // Return the generated HTML wrapped in a JSON response
        return response()->json(['html' => $html]);
    }

    public function loadSubTypes(Request $request)
    {
        // Retrieve type_name from the request
        $type_name = $request->type_name;
        
        // Initialize the HTML variable for storing the select dropdown
        $html = '';

        // Query to get the subtypes based on the type_name
        $subtypesQuery = Product::select('subtype')
            ->where('subtype', '!=', '') // Exclude empty subtypes
            ->groupBy('subtype'); // Group by subtype to get distinct values

        // If a type_name is provided, apply the filter for type
        if ($type_name) {
            $subtypesQuery->where('type', 'like', $type_name);
        }

        // Execute the query
        $subtypes = $subtypesQuery->get();

        // Check if subtypes were found
        if (!$subtypes->isEmpty()) {
            // Generate HTML for the dropdown with found subtypes
            $html .= '<div class="bootstrap-select fm-cmp-mg" id="subtypes_load">
                        <select class="form-control" name="subtype" required="required">';
            $html .= '<option value="0">All</option>';

            // Loop through the subtypes and create the dropdown options
            foreach ($subtypes as $product) {
                $html .= '<option value="' . $product->subtype . '">' . $product->subtype . '</option>';
            }

            $html .= '</select></div>';
        } else {
            // If no subtypes were found, you can either return an empty select or a custom message
            // Here we generate a fallback select dropdown with a default "No subtypes available" option
            $html .= '<div class="bootstrap-select fm-cmp-mg" id="subtypes_load">
                        <select class="form-control" name="subtype" required="required">';
            $html .= '<option value="0">No subtypes available</option>';
            $html .= '</select></div>';
        }

        // Return the generated HTML wrapped in a JSON response
        return response()->json(['html' => $html]);
    }

    // public function loadSubTypes(Request $request)
    // {
    //     $type_name = $request->type_name;
    //     $html = '';

    //     $subtypes=Product::select('subtype')->where('type' , 'like' , $type_name)->where('subtype' , '!=' , '')->groupBy('subtype')->get();

    //     if(!empty($subtypes) && count($subtypes) > 0)
    //     {
    //         $html .='<div class="bootstrap-select fm-cmp-mg" id="subtypes_load">
    //                     <select class="form-control" name="type" required="required">';
    //                         $html .= '<option value="0">All</option>';
    //                         foreach($subtypes as $product)
    //                         {
    //                             $html .= '<option value="'.$product->subtype.'"> ' . $product->subtype . ' </option>';
    //                         }
    //             $html .= '</select></div>';
    //     }
    //     else
    //     {
    //         $subtypes=Product::select('subtype')->where('subtype' , '!=' , '')->groupBy('subtype')->get();

    //         $html .='<div class="bootstrap-select fm-cmp-mg" id="subtypes_load">
    //                     <select class="form-control" name="type" required="required">';
    //                         $html .= '<option value="0">All</option>';
    //                         foreach($subtypes as $product)
    //                         {
    //                             $html .= '<option value="'.$product->subtype.'"> ' . $product->subtype . ' </option>';
    //                         }
    //             $html .= '</select></div>';
    //     }

    //     return response()->json(['html' => $html]);
    // } // End Method


    // public function updateprice(Request $request)
    // {
    //     if(Auth::user()->can('inventory.prices.update'))
    //     {
    //         if($request->input('unit_id') !='' && $request->input('price')=='')
    //         {
    //             $records = Product::where('unitid' , $request->input('unit_id'))->where('status', 'Available')->paginate(50);
    //             $projects = Project::orderBy('id', 'ASC')->get();
    //             $categories = Category::orderBy('id', 'ASC')->get();
    //             $buildings =Product::select('building')->where('building' , '!=' , '')->groupBy('building')->get();
    //             $types =Product::select('type')->where('type' , '!=' , '')->groupBy('type')->get();
    //             $subtypes =Product::select('subtype')->where('subtype' , '!=' , '')->groupBy('subtype')->get();

    //             if(count($records) > 0)
    //             {
    //             return view('products.price', compact('records','projects', 'categories' , 'buildings' , 'types' , 'subtypes'))->with('message','Record Found.');
    //             }else{
    //                 return view('products.price', compact('records','projects', 'categories' , 'buildings' , 'types' , 'subtypes'))->with('error_message' , 'No Record Found..!');
    //             }
    //         }

    //         $condition = array();
    //         $condition[] = array('status', 'Available');

    //         if ($request->input('floor') > 0)
    //         {
    //             $condition[] = array('floor', $request->input('floor'));
    //         }
    //         if ($request->input('corner_percent') > 0)
    //         {
    //             $condition[] = array('corner', 1 );
    //         }
    //         if ($request->input('unit_id') != '') {
    //             $condition[] = array('unitid', $request->input('unit_id'));
    //         }
    //         if ($request->input('building') != 0)
    //         {
    //             $condition[] = array('building', $request->input('building'));
    //         }
    //         if ($request->input('type') != 0)
    //         {
    //             $condition[] = array('type', $request->input('type'));
    //         }
    //         if ($request->input('subtype') != 0)
    //         {
    //             $condition[] = array('subtype', $request->input('subtype'));
    //         }

    //         if ($request->input('project_id') > 0) {
    //             $condition[] = array('project_id', $request->input('project_id'));
    //         }

    //         if ($request->input('category_id') > 0) {
    //             $condition[] = array('category_id', $request->input('category_id'));
    //         }

    //         $products = Product::where($condition)->get();

    //         if(count($products) > 0)
    //         {
    //             foreach ($products as $product)
    //             {
    //                 if(is_numeric($request->input('price')))
    //                 {
    //                     $product_price = str_replace(',', '', $product->carea) * $request->input('price');
    //                     $corner_amount=null;

    //                     if($request->corner=="corner" && $request->corner_percent !='')
    //                     {
    //                         if(is_numeric($request->corner_percent))
    //                         {
    //                             $corner_amount = ($product_price/100)*$request->corner_percent;
    //                             // echo $request->corner_percent."% Pertcent Amount of ".$product_price." Is = ".$corner_amount; echo "<br>";
    //                             $product_price = $product_price + $corner_amount;
    //                             // echo "Now Total Price Is = ".$product_price; echo "<br>";exit;
    //                         }else{
    //                             return redirect()->back()->with('error' , 'Corner Price is Not in Numaric Numbers.');
    //                         }
    //                     }

    //                     // $down_amount = $product_price * ($product->projectname->dpayment / 100);
    //                     $down_amount = $product_price * ($product->dpaymentper/100);   // discounted_price = original_price * (discount / 100)

    //                     // $posession_amount = $product_price * ($product->projectname->pamount / 100);
    //                     $posession_amount = $product_price * ($product->posperamount/100);

    //                     $remain_amount = $product_price - ($down_amount + $posession_amount);

    //                     // $quater_installment = $remain_amount / $product->projectname->nofqtrly;
    //                     $quater_installment = $remain_amount / $product->numinstallment;

    //                     // $monthly_installment = $remain_amount / $product->projectname->nofmonthly;
    //                     $monthly_installment = $remain_amount / $product->nummoninstallment;

    //                     // echo $product->name.' Price ='.$product_price.'-- Downpayment 30% ='.$down_amount.'--- Pocession amount 10% ='.$posession_amount.'----Qtr Installment ='.$quater_installment.'====Monthly Installment = '.$monthly_installment;
    //                     // exit;

    //                     $product->carea = str_replace(',', '', $product->carea);
    //                     $product->price = $product_price;
    //                     $product->dpayment = $down_amount;
    //                     $product->posamount = $posession_amount;
    //                     $product->qtrinstallment = $quater_installment;
    //                     $product->moninstallment = $monthly_installment;
    //                     $product->psft = $request->input('price');
    //                     $product->corner_amt = $corner_amount;
    //                     $product->save();

    //                 } else {
    //                     // echo "Price is Not in Numaric Numbers  ";  echo "<br>";
    //                     // echo $product->id.'---'.$product->carea;
    //                     // exit;
    //                     return redirect()->back()->with('error' , 'Price is Not in Numaric Numbers.');
    //                 }

    //             }

    //             $projects = Project::orderBy('id', 'ASC')->get();
    //             $categories = Category::orderBy('id', 'ASC')->get();
    //             $buildings =Product::select('building')->where('building' , '!=' , '')->groupBy('building')->get();
    //             $types =Product::select('type')->where('type' , '!=' , '')->groupBy('type')->get();
    //             $subtypes =Product::select('subtype')->where('subtype' , '!=' , '')->groupBy('subtype')->get();
    //             $records = Product::where($condition)->orderBy('unitid', 'asc')->paginate(50);

    //             return view('products.price', compact('records','projects', 'categories' , 'buildings' , 'types' , 'subtypes'))->with('message','Price Updated Successfully.');

    //         }
    //         else
    //         {
    //             return redirect()->back()->with('error' , 'No Record Found..!');
    //         }
    //     }
    //     else{
    //         return redirect('/')->withErrors(__('Doesn\'t have permission to access this resource'));
    //     }
    // }

    public function updateprice(Request $request)
    {
        // echo "<pre>";print_r($request->all()); //exit;

        if(Auth::user()->can('inventory.prices.update'))
        {
            if($request->input('unit_id') !='' && $request->input('price')=='')
            {
                $records = Product::where('unitid' , $request->input('unit_id'))->where('status', 'Available')->paginate(50);
                $projects = Project::orderBy('id', 'ASC')->get();
                $categories = Category::orderBy('id', 'ASC')->get();
                $buildings =Product::select('building')->where('building' , '!=' , '')->groupBy('building')->get();
                $types =Product::select('type')->where('type' , '!=' , '')->groupBy('type')->get();
                $subtypes =Product::select('subtype')->where('subtype' , '!=' , '')->groupBy('subtype')->get();

                if(count($records) > 0)
                {
                return view('products.price', compact('records','projects', 'categories' , 'buildings' , 'types' , 'subtypes'))->with('message','Record Found.');
                }else{
                    return view('products.price', compact('records','projects', 'categories' , 'buildings' , 'types' , 'subtypes'))->with('error_message' , 'No Record Found..!');
                }
            }

            $condition = array();
            $condition[] = array('status', 'Available');

            if ($request->input('floor') > 0)
            {
                $condition[] = array('floor', $request->input('floor'));
            }

            // Check for corner value
            if (($request->input('corner_percent') > 0 || $request->input('corner_percent') !='')  && $request->input('corner') == 'corner') {
                $condition[] = ['corner', 1]; // Use [] for array notation
            }
            // Check for corner value
            if ($request->input('corner') == 'non-corner') {
                $condition[] = ['corner', 0]; // Use [] for array notation
            }

            // echo "<pre>";print_r($condition); exit;

            if ($request->input('unit_id') != '') {
                $condition[] = array('unitid', $request->input('unit_id'));
            }
            if ($request->input('building') != 0)
            {
                $condition[] = array('building', $request->input('building'));
            }
            if ($request->input('type') != 0)
            {
                $condition[] = array('type', $request->input('type'));
            }
            if ($request->input('subtype') != 0)
            {
                $condition[] = array('subtype', $request->input('subtype'));
            }

            if ($request->input('project_id') > 0) {
                $condition[] = array('project_id', $request->input('project_id'));
            }

            if ($request->input('category_id') > 0) {
                $condition[] = array('category_id', $request->input('category_id'));
            }

            $products = Product::where($condition)->get();

            // if ($products->isNotEmpty())
            if(count($products) >= 1)
            {
                foreach ($products as $product)
                {
                    if(is_numeric($request->input('price')))
                    {
                        $change_price = null;

                        // Determine how the price should be changed
                        switch ($request->input('change-price')) {
                            case 'update-price':
                                $change_price = $request->input('price');
                                break;
            
                            case 'increase-price':
                                $change_price = $product->psft + $request->input('price');
                                break;
            
                            case 'decrease-price':
                                $change_price = $product->psft - $request->input('price');
                                break;
            
                            default:
                                return redirect()->back()->with('error', 'plase select a price-type option.');
                        }

                        $product_price = str_replace(',', '', $product->carea) * $change_price;
                        $corner_amount=null;

                        // If corner price adjustment is required
                        if ($request->corner == "corner" && $request->corner_percent != '') 
                        {
                            // echo "YES"; exit;
                            if (is_numeric($request->corner_percent)) {
                                $corner_amount = ($product_price / 100) * $request->corner_percent;
                                $product_price += $corner_amount;
                            } else {
                                return redirect()->back()->with('error', 'Corner price must be a numeric value.');
                            }
                        }

                        // $down_amount = $product_price * ($product->projectname->dpayment / 100);
                        $down_amount = $product_price * ($product->dpaymentper/100);   // discounted_price = original_price * (discount / 100)

                        // $posession_amount = $product_price * ($product->projectname->pamount / 100);
                        $posession_amount = $product_price * ($product->posperamount/100);

                        $remain_amount = $product_price - ($down_amount + $posession_amount);

                        // $quater_installment = $remain_amount / $product->projectname->nofqtrly;
                        $quater_installment = $remain_amount / $product->numinstallment;

                        // $monthly_installment = $remain_amount / $product->projectname->nofmonthly;
                        $monthly_installment = $remain_amount / $product->nummoninstallment;

                        // echo $product->name.' Price ='.$product_price.'-- Downpayment 30% ='.$down_amount.'--- Pocession amount 10% ='.$posession_amount.'----Qtr Installment ='.$quater_installment.'====Monthly Installment = '.$monthly_installment;
                        // exit;

                        $product->carea = str_replace(',', '', $product->carea);
                        $product->price = $product_price;
                        $product->dpayment = $down_amount;
                        $product->posamount = $posession_amount;
                        $product->qtrinstallment = $quater_installment;
                        $product->moninstallment = $monthly_installment;
                        $product->psft = $change_price;
                        $product->corner_amt = $corner_amount;
                        $product->save();

                    } else {
                        // echo "Price is Not in Numaric Numbers  ";  echo "<br>";
                        // echo $product->id.'---'.$product->carea;
                        // exit;
                        return redirect()->back()->with('error' , 'Price is Not in Numaric Numbers.');
                    }

                }

                $projects = Project::orderBy('id', 'ASC')->get();
                $categories = Category::orderBy('id', 'ASC')->get();
                $buildings =Product::select('building')->where('building' , '!=' , '')->groupBy('building')->get();
                $types =Product::select('type')->where('type' , '!=' , '')->groupBy('type')->get();
                $subtypes =Product::select('subtype')->where('subtype' , '!=' , '')->groupBy('subtype')->get();
                $records = Product::where($condition)->orderBy('unitid', 'asc')->paginate(50);

                return view('products.price', compact('records','projects', 'categories' , 'buildings' , 'types' , 'subtypes'))->with('message','Price Updated Successfully.');

            }
            else
            {
                return redirect()->back()->with('error' , 'No Record Found..!');
            }
        }
        else{
            return redirect('/')->withErrors(__('Doesn\'t have permission to access this resource'));
        }
    }

    public function search(Request $request)
    {
        if(Auth::user()->can('inventory.view'))
        {
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
            if ($request->input('corner') != 'all' && $request->input('corner') != '')
            {
                if($request->input('corner') == 'none_corner' ){
                    $corner = 0 ;
                }if($request->input('corner') == 'corner' ){
                    $corner = 1 ;
                }

                $condition[] = array('corner', $corner);
            }

            $clients = array();
            if ($request->input('name') || $request->input('unit_id') != '' || $request->input('project_id') != 0 || $request->input('category_id') != 0 || $request->input('floor') != '') {
                // $records = Product::where($condition)->orderBy('id', 'desc')->paginate(30);
                if(Auth::user()->role==11 || Auth::user()->role==12 ){
                    $records = Product::where($condition)->where('status', 'Available')->orderBy('id', 'asc')->paginate(30);
                }else{
                    $records = Product::where($condition)->orderBy('id', 'desc')->paginate(30);
                }
            }
            else{
                $records = Product::orderBy('id', 'desc')->paginate(30);
            }

            $projects = Project::orderBy('id', 'ASC')->get();
            $categories = Category::orderBy('id', 'ASC')->get();
            $products = Product::orderBy('created_at', 'desc')->get();
            $company = Company::orderBy('id', 'ASC')->get();
            return view('products.index', compact('records', 'projects', 'categories','products','company'));
        }else{
            return redirect('/')->withErrors(__('Doesn\'t have permission to access this resource'));
        }
    }

    public function existence(Request $request)
    {
        if(Auth::user()->can('inventory.existence.change'))
        {
            if ($request->date == '' || $request->note == '' || $request->company == '' || $request->status == '' )
            {
                return redirect()->back()->withErrors(__('Please Fill All The Fields'));
            }

            $date_select = date("Y-m-d", strtotime($request->date)).' '.date('H:i:s');

            if($request->status == 'Available')
            {
                $hold_status = 0;
                $hold_by = 0;
                $date = null;
                $sold_by = $request->status;
            }
            else
            {
                $hold_status = 1;
                $hold_by = $request->hold_by;
                $date = $date_select;
                $sold_by = $request->status.' By '.$request->company;
            }

            $is_approved = 0;
            $approved_by = 0;

            if($request->status == 'Sold')
            {
                $is_approved = 1;
                $approved_by = auth()->user()->id;
            }

            LeadNotes::insert([
                'notes' => $request->note,
                'status' => 1,
                'lead_id' => 0,
                'item_id' => $request->product_id,
                'hold_by' => $hold_by,
            ]);


            Product::where('id', $request->product_id)
                    ->update([
                        'hold_by' => $hold_by,
                        'hold_expiary' => $date,
                        'hold_status' => $hold_status,
                        'status' =>  $request->status,
                        'sold_by' =>  $sold_by,
                        'is_approved' => $is_approved,
                        'approved_by' => $approved_by,
                        'sold_at' => Carbon::now(),
            ]);
            return redirect('inventory')->withStatus(__('Submitted Successfully.'));
        }
        else{
            return redirect('/')->withErrors(__('Doesn\'t have permission to access this resource'));
        }
    }

    public function approval_store(Request $request)
    {
        if(Auth::user()->can('inventory.approve.option'))
        {
            if ($request->is_approved == '' || $request->approved_by == '' || $request->product_id == '' )
            {
                return redirect()->back()->withErrors(__('Please Fill All The Fields'));
            }

            $product=Product::where('id',$request->product_id)->first();
            if($request->is_approved == 1)
            {
                $is_approved = 1;
                $approved_by = $request->approved_by;
                $hold_status = $product->hold_status;
                $hold_by =  $product->hold_by;
                $sold_by = $product->sold_by;
                $date = $product->hold_expiary;
                $status = 'Sold';
                $order_status = 'Sold';
                $sold_at = Carbon::now();

                // Notification Only For User who booked the inventory

                    $data = array(
                        'type' => 'Inventory '.$status,
                        'msg_body' => base64_encode('Inventory Has Been '.$status.' By '.$product->holded_by->name. ' And Approved By ' .Auth::user()->name.' Unit ID : <a href="'.url("/inventory-search?unit_id=$product->unitid&submit=Filter").'">'.$product->unitid.'</a>'),
                        'created_by' => Auth::user()->id,
                        'show_to' => $product->hold_by,
                        'show_to_role' => 0,        // 0 for no One
                        'redirect' => 'inventory',
                    );
                    Helper::notification($data);

                // ======Send Notification To Parent User (who booked the inventory)====

                    $data = array(
                        'type' => 'Inventory '.$status,
                        'msg_body' => base64_encode('Inventory Has Been '.$status.' By '.$product->holded_by->name. ' And Approved By ' .Auth::user()->name.' Unit ID : <a href="'.url("/inventory-search?unit_id=$product->unitid&submit=Filter").'">'.$product->unitid.'</a>'),
                        'created_by' => Auth::user()->id,
                        'show_to' => $product->holded_by->parent,
                        'show_to_role' => 0,
                        'redirect' => 'inventory',
                    );
                    Helper::notification($data);

                // ======Send Notification To Role User====

                    $roleUsers=User::where('role' , 5)->get();

                    foreach($roleUsers as $roleUser)
                    {
                        if($product->holded_by->parent != $roleUser->id)
                        {
                            $data = array(
                                'type' => 'Inventory '.$status,
                                'msg_body' => base64_encode('Inventory Has Been '.$status.' By '.$product->holded_by->name. ' And Approved By ' .Auth::user()->name.' Unit ID : <a href="'.url("/inventory-search?unit_id=$product->unitid&submit=Filter").'">'.$product->unitid.'</a>'),
                                'created_by' => Auth::user()->id,
                                'show_to' => $roleUser->id, // Show to role Users as a id
                                'show_to_role' => 0,
                                'redirect' => 'inventory',
                            );
                            Helper::notification($data);
                        }
                    }
                // ==========Notification End==========

            }

            if($request->is_approved == 0)
            {
                $is_approved = 0;
                $approved_by = 0;
                $hold_status = 0;
                $hold_by = 0;
                $date = null;
                $sold_by = 'Available';
                $status = 'Available';
                $sold_at = null;
                $order_status = 'Rejected';

                // Notification Only For User who booked the inventory

                    $data = array(
                        'type' => 'Inventory Rejected',
                        'msg_body' => base64_encode('Inventory Has Been Rejected By ' .Auth::user()->name.' Unit ID : <a href="'.url("/inventory-search?unit_id=$product->unitid&submit=Filter").'">'.$product->unitid.'</a>'.' Were Booked By '.$product->holded_by->name),
                        'created_by' => Auth::user()->id,
                        'show_to' => $product->hold_by,
                        'show_to_role' => 0,         // 0 for no One
                        'redirect' => 'inventory',
                    );
                    Helper::notification($data);

                // ======Send Notification To Parent User (who booked the inventory)====
                    $data = array(
                        'type' => 'Inventory Rejected',
                        'msg_body' => base64_encode('Inventory Has Been Rejected By ' .Auth::user()->name.' Unit ID : <a href="'.url("/inventory-search?unit_id=$product->unitid&submit=Filter").'">'.$product->unitid.'</a>'.' Were Booked By '.$product->holded_by->name),
                        'created_by' => Auth::user()->id,
                        'show_to' => $product->holded_by->parent,
                        'show_to_role' => 0,         // 0 for no One
                        'redirect' => 'inventory',
                    );
                    Helper::notification($data);

                // ======Send Notification To Role User====

                    $roleUsers=User::where('role' , 5)->get();

                    foreach($roleUsers as $roleUser)
                    {
                        if($product->holded_by->parent != $roleUser->id)
                        {
                            $data = array(
                                'type' => 'Inventory Rejected',
                                'msg_body' => base64_encode('Inventory Has Been Rejected By ' .Auth::user()->name.' Unit ID : <a href="'.url("/inventory-search?unit_id=$product->unitid&submit=Filter").'">'.$product->unitid.'</a>'.' Were Booked By '.$product->holded_by->name),
                                'created_by' => Auth::user()->id,
                                'show_to' => $roleUser->id, // Show to role Users as a id
                                'show_to_role' => 0,
                                'redirect' => 'inventory',
                            );
                            Helper::notification($data);
                        }
                    }
                // ==========Notification End==========
            }

            // echo "Done"; exit;

            $order=Order::where('unit_id',$product->unitid)->where('status', '!=' , 'Rejected')->where('approved_by', 0)->latest()->first();
            if($order)
            {
                $order->approved_by=$request->approved_by;
                $order->status= $order_status;
                $order->save();
            }
            Product::where('id', $request->product_id)
                ->update([
                    'hold_by' => $hold_by,
                    'hold_expiary' => $date,
                    'hold_status' => $hold_status,
                    'status' =>  $status,
                    'sold_by' =>  $sold_by,
                    'is_approved' => $is_approved,
                    'approved_by' => $approved_by,
                    'sold_at' => $sold_at,
            ]);
            ProductNotes::insert([
                'note' => $request->note,
                'status' => $status,
                'item_id' => $request->product_id,
                'added_by' => Auth()->user()->id,
            ]);
            return redirect('inventory')->withStatus(__('Submitted Successfully.'));
        }
        else{
            return redirect('/')->withErrors(__('Doesn\'t have permission to access this resource'));
        }
    }

    public function showdiscount()
    {
        if(Auth::user()->can('discount.prices.view'))
        {
            $discount = Discount::orderBy('id', 'ASC')->get();
            return view('products.discount', compact('discount'));
        }        else{
            return redirect('/')->withErrors(__('Doesn\'t have permission to access this resource'));
        }
    }

    public function discountEdit(Request $request)
    {
        if(Auth::user()->can('discount.prices.edit'))
        {
            $discount = Discount::where('id', $request->id)->first();
            return view('products.discount_edit', compact('discount'));
        }
        else{
            return redirect('/')->withErrors(__('Doesn\'t have permission to access this resource'));
        }
    }

    public function discountUpdate(Request $request)
    {
        if(Auth::user()->can('discount.prices.edit')){
            $discount = Discount::where('id', $request->id)->first();
            $discount->sqft_amount = $request->input('sqft_amount');
            $discount->token_amount = $request->input('token_amount');
            $discount->save();
            return redirect('/discountprice')->withStatus(__('Discount Amount Successfully Updated.'));
        }else{
            return redirect('/')->withErrors(__('Doesn\'t have permission to access this resource'));
        }
    }

    public function get_orders(Request $request)
    {
        $orders = Order::where('unit_id', $request->unit_id)->orderby('id', 'desc')->get();

        $rows = '';

        if (count($orders) > 0)
        {
            foreach ($orders as $order)
            {
                if($order->approved_by > 0){
                    $approved_by=$order->approved->name;
                }else{
                    $approved_by= 'No One';
                }

                $rows .= ''
                    . '<tr>'
                        . '<td>' . '<strong>' . $order->unit_id . '</strong>'. '</td>'
                        . '<td>' . '<strong>' . $order->user->name . '</strong>'. '</td>'
                        . '<td>' . '<strong>' . $order->lead_id . '</strong>'. '</td>'
                        . '<td>' . '<strong>' . $order->status . '</strong>'. '</td>'
                        . '<td>' . '<strong>' . $order->discount . '</strong>'. '</td>'
                        . '<td>' . '<strong>' . $order->price . '</strong>'. '</td>'
                        . '<td>' . '<strong>' . $order->token . '</strong>'. '</td>'
                        . '<td>' . '<strong>' . $order->token_received . '</strong>'. '</td>'
                        . '<td>' . '<strong>' . $order->client->name . '</strong>'. '</td>'
                        . '<td>' . '<strong>' .$approved_by.'</strong>'. '</td>'
                    . '</tr>';
            }
        }
        else
        {
            $rows .= ''
                . '<tr>'
                    . '<td colspan="10">' . '<strong> No Order Found! </strong>'. '</td>'

                . '</tr>';
        }

        return response()->json(['html' => $rows]);
    }

    public function getUnitIds($unitids)
     {
        if(Auth::user()->can('inventory.view'))
        {
            $unitIdArray = explode(',', $unitids);
            $records = Product::whereIn('unitid', $unitIdArray)->orderBy('created_at', 'asc')->paginate(50);

            $projects = Project::orderBy('id', 'ASC')->get();
            $categories = Category::orderBy('id', 'ASC')->get();
            $company = Company::orderBy('id', 'ASC')->get();
            return view('products.index', compact('records', 'projects', 'categories' , 'company' ));
        }else{
            return redirect('/')->withErrors(__('Doesn\'t have permission to access this resource'));
        }
    }


}
