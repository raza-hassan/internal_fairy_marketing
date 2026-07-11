<?php
namespace App\Http\Controllers\admin;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Exports\InventoryExport;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\Product;
use App\Models\Category;
use App\Models\Company;
use App\Models\Project;
use Illuminate\Support\Facades\Auth;
class FileController extends Controller {
    private $rows = [];


    public function fileImportExport()
    {
        if(Auth::user()->can('inventory.import')){
            return view('admin.products.import');
        }else{
            return redirect('/')->withErrors(__('Doesn\'t have permission to access this resource'));
        }
    }

    public function fileExport() {
        return Excel::download(new UsersExport, 'users-collection.xlsx');
    }

    public function fileImport(Request $request)
    {
        if(Auth::user()->can('inventory.import')){

            $path = $request->file('file')->getRealPath();
            $records = array_map('str_getcsv', file($path));
            if (!count($records) > 0) {
                return 'Error...';
            }
            // echo '<pre>';
            // print_r($records[0]);
            // exit;
            // Get field names from header column
            $fields = array_map('strtolower', preg_replace('/[^a-zA-Z0-9\']/', '', $records[0]));
            // $newstr = preg_replace('/[^a-zA-Z0-9\']/', '', "There wouldn't be any");
            // $newstr = str_replace("'", '', $newstr);
            // $fields = array_map('strtolower', str_replace('/', '', $fields));
            // $fields = array_map('strtolower', str_replace('%', '', $fields));
            // Remove the header column
            array_shift($records);
            foreach ($records as $record) {
                if (count($fields) != count($record)) {
                    return 'csv_upload_invalid_data';
                }
                // Decode unwanted html entities
                $record = array_map("html_entity_decode", $record);
                // Set the field name as key
                $record = array_combine($fields, $record);
                // Get the clean data
                $this->rows[] = $this->clear_encoding_str($record);
            }
            // echo '<pre>';
            // print_r($this->rows);
            // exit;
            $totalamount = 0;
            $downpayment = 0;
            $possessionamount = 0;
            $quarterlyinstallment = 0;
            $monthlyinstallment = 0;
            foreach ($this->rows as $data) {
                $name = '';
                if (trim($data['category']) == 'Commercial') {
                    $category = Category::where('name', trim($data['type']))->first();
                    $name = $data['title'] . '-' . trim($data['type']);
                } else {
                    $category = Category::where('name', '=', trim($data['category']))->first();
                    $name = $data['title'] . '-' . trim($data['category']);
                }
                $category_id = 0;
                if (!empty($category)) {
                    $category_id = $category->id;
                }
                // echo $data['unitid']; exit;
                $product = Product::where('unitid', '=', $data['title'])->first();
                $totalamount = str_replace(',', '', $data['totalamount']);
                $downpayment = str_replace(',', '', $data['downpayment']);
                $possessionamount = str_replace(',', '', $data['possessionamount']);
                $quarterlyinstallment = str_replace(',', '', $data['quarterlyinstallment']);
                $monthlyinstallment = str_replace(',', '', $data['monthlyinstallment']);
                $pricepsft = str_replace(',', '', $data['pricepsft']);
                $carea = str_replace(',', '', $data['coveredarea']);
                $grossarea = str_replace(',', '', $data['grossarea']);
                if($data['status'] == 'Available'){
                    $hold_status = 0;
                }else{
                    $hold_status = 1;
                }
                $records = array();
                $records = array(
                    'name' => $name,
                    'unitid' => trim($data['title']),
                    'carea' => trim($carea),
                    'garea' => trim($grossarea),
                    'floor' => trim($data['floor']),
                    'sbm' => trim($data['skusbm']),
                    'sku' => trim($data['skusbm']),
                    'dpayment' => $downpayment,
                    'rpayment' => 0,
                    'qtrinstallment' => $quarterlyinstallment,
                    'numinstallment' => trim($data['numberofquarterlyinstallment']),
                    'posamount' => $possessionamount,
                    'moninstallment' => $monthlyinstallment,
                    'nummoninstallment' => trim($data['numberofmonthlyinstallment']),
                    'posperamount' => 10,
                    'dpaymentper' => 30,
                    'type' => trim($data['type']),
                    'subtype' => trim($data['subtype']),
                    'building' => trim($data['building']),
                    'description' => '',
                    'price' => $totalamount,
                    'psft' => $pricepsft,
                    'stock' => 1,
                    'status' => trim($data['status']),
                    'project_id' => 1,
                    'category_id' => $category_id,
                    'hold_expiary' => '',
                    'hold_status' => $hold_status,
                    'sold_by' => trim($data['inventorystatus']),
                );
                array_filter($records);
                if ($product === null) {
                    Product::create($records);
                } else {
                    // if($category_id == 1){
                    // echo '<pre>';
                    // print_r($records);
                    // $product->update($records);
                    // exit;
                    // }
                    $product->update($records);
                }
            }
            return redirect('inventory')->withStatus(__('Products successfully Imported.'));

        }else{
            return redirect('/')->withErrors(__('Doesn\'t have permission to access this resource'));
        }
    }

        public function sizesFileImport(Request $request)
    {
        if(Auth::user()->can('inventory.import'))
        {
            $path = $request->file('file')->getRealPath();
            $records = array_map('str_getcsv', file($path));
            if (!count($records) > 0) {
                return 'Error...';
            }

            $fields = array_map('strtolower', preg_replace('/[^a-zA-Z0-9\']/', '', $records[0]));
            array_shift($records);

            foreach ($records as $record)
            {
                if (count($fields) != count($record))
                {
                    return 'csv_upload_invalid_data';
                }
                // Decode unwanted html entities
                $record = array_map("html_entity_decode", $record);
                // Set the field name as key
                $record = array_combine($fields, $record);
                // Get the clean data
                $this->rows[] = $this->clear_encoding_str($record);
            }

            foreach ($this->rows as $data)
            {
                $unitId = $data['unitid'];
                $area = $data['area'];

                if (!empty($area) && $area !== 0 && !empty($unitId) && $unitId !== 0)
                {
                    // Handle SERVENT separately
                    if (strpos($unitId, 'SERVENT') !== false)
                    {
                        Product::where('unitid', 'LIKE', '%SERVENT%')
                            ->update(['carea' => $area]);
                    } else {
                        Product::where('unitid', $unitId)
                            ->update(['carea' => $area]);
                    }
                }
            }

            return redirect('inventory')->withStatus(__('Products sizes updated according to file.'));

        }else{
            return redirect('/')->withErrors(__('Doesn\'t have permission to access this resource'));
        }

    }

    private function clear_encoding_str($value) {
        if (is_array($value)) {
            $clean = [];
            foreach ($value as $key => $val) {
                $clean[$key] = mb_convert_encoding($val, 'UTF-8', 'UTF-8');
            }
            return $clean;
        }
        return mb_convert_encoding($value, 'UTF-8', 'UTF-8');
    }


    public function inventoryExportSearch()
    {
        if(Auth::user()->can('inventory.export')){
            $projects = Project::orderBy('id', 'ASC')->get();
            $categories = Category::orderBy('id', 'ASC')->get();
            $company = Company::orderBy('id', 'ASC')->get();
            return view('admin.products.export' , compact('projects', 'categories' , 'company' ));
        }else{
            return redirect('/')->withErrors(__('Doesn\'t have permission to access this resource'));
        }
    }

    public function exportInventoryFile(Request $request)
    {
        if(Auth::user()->can('inventory.export'))
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

            // Search in the title and body columns from the posts table
            $products = Product::where($condition)->get();

            return Excel::download(new InventoryExport($products), 'products.csv');

        }
        else{
            return redirect('/')->withErrors(__('Doesn\'t have permission to access this resource'));
        }
    }

}
