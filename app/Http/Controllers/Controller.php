<?php

namespace App\Http\Controllers;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use App\Models\Affiliator;
use App\Models\Clients;
use App\Models\Product;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function move_numbers()
    {
        $affiliators = Affiliator::all();
        foreach ($affiliators as $record)
        {
            if ($record->phone != '')
            {
                $phone = $record->phone;
                DB::table('numbers')->insert( array( 'number' => $phone, 'type' => 'affiliators' , 'client_id' => $record->id ));
            }
            if ($record->telephone != '')
            {
                $phone = $record->telephone;
                DB::table('numbers')->insert( array( 'number' => $phone, 'type' => 'affiliators' ,'client_id' => $record->id ));
            }
            if ($record->telephone1 != '')
            {
                $phone = $record->telephone1;
                DB::table('numbers')->insert( array('number' => $phone,'type' => 'affiliators' ,'client_id' => $record->id ));
            }
        }

        $clients = Clients::all();
        foreach ($clients as $record)
        {
            if ($record->phone != '')
            {
                $phone = $record->phone;
                DB::table('numbers')->insert( array('number' => $phone,'type' => 'clients' ,'client_id' => $record->id ));
            }
            if ($record->telephone != '')
            {
                $phone = $record->telephone;
                DB::table('numbers')->insert( array('number' => $phone,'type' => 'clients' ,'client_id' => $record->id ));
            }
            if ($record->telephone1 != '')
            {
                $phone = $record->telephone1;
                DB::table('numbers')->insert( array('number' => $phone,'type' => 'clients' ,'client_id' => $record->id ));
            }
        }

        echo "All numbers has been add into Table";
    }

    public function bookStatus()
    {
        return view('file_upload');
    }

    public function readFile(Request $request)
    {
        // Select File
        $path = $request->file('file')->getRealPath();

        // Geting file Record
        $records = array_map('str_getcsv', file($path));
        // echo '<pre>';print_r($records[0]);exit;

        if (!count($records) > 0)
        {
            echo 'There Is No Record In This File'; exit;
        }

        // Get field names from header column with lower letter
        $column_names = array_map('strtolower', preg_replace('/[^a-zA-Z0-9\']/', '', $records[0]));
        // echo '<pre>';print_r($fields);exit;

        array_shift($records);

        foreach ($records as $record)
        {
            if (count($column_names) != count($record))
            {
                echo 'CSV Upload Invalid Data'; exit;
            }

            // Decode unwanted html entities
            $record = array_map("html_entity_decode", $record);

            // Set the field name as a key
            $record = array_combine($column_names, $record);
            // echo '<pre>';print_r($record);exit;

            // Get the clean data
            $rows[] = $this->clear_encoding_str($record);
        }

        // // Show All Data
        // echo '<pre>';print_r($rows);exit;

        foreach ($rows as $data)
        {
            // echo '<pre>';print_r($data['title']);exit;

            if(trim($data['title']))
            {
                // $product = Product::where('unitid', trim($data['title']))->first();
                // $product->status = trim($data['status']);
                // $product->sold_by = trim($data['inventorystatus']);

                Product::where('unitid', trim($data['title']))->update([
                    "status" => trim($data['status']),
                    "sold_by" => trim($data['inventorystatus']),
                ]);
            }
        }
        return redirect('inventory')->withStatus(__('Products Status Successfully Updated.'));
    }

    private function clear_encoding_str($value)
    {
        if (is_array($value))
        {
            $clean = [];
            foreach ($value as $key => $val)
            {
                $clean[$key] = mb_convert_encoding($val, 'UTF-8', 'UTF-8');
            }
            return $clean;
        }
        return mb_convert_encoding($value, 'UTF-8', 'UTF-8');
    }

    public function chooseFile()
    {
        return view('choose_file');
    }

    public function updateprice(Request $request)
    {
        // echo "<pre>"; print_r($request->all()); exit;

        // Select File
        $path = $request->file('file')->getRealPath();

        // Geting file Record (head)
        $records = array_map('str_getcsv', file($path));
        // echo '<pre>';print_r($records[0]);exit;

        // Get field names from header column with lower letter
        $column_names = array_map('strtolower', preg_replace('/[^a-zA-Z0-9\']/', '', $records[0]));
        // echo '<pre>';print_r($column_names);exit;

        array_shift($records);

        foreach ($records as $record)
        {
            if (count($column_names) != count($record))
            {
                echo 'CSV Upload Invalid Data'; exit;
            }

            // Decode unwanted html entities
            $record = array_map("html_entity_decode", $record);

            // Set the field name as a key
            $record = array_combine($column_names, $record);
            // echo '<pre>';print_r($record);exit;

            // Get the clean data
            $rows[] = $this->clear_encoding_str($record);
        }

        // // Show All Data
        // echo '<pre>';print_r($rows);exit;
        $counter = 0;
        foreach ($rows as $data)
        {
            //  echo '<pre>';
            // print_r($data);exit;

            if(trim($data['title']))
            {
                $product = Product::where('unitid', trim($data['title']))->where('status', '!=','Sold')->first();
                // echo "<pre>";print_r($product); exit;

                if($product)
                {
                    $price =str_replace(',', '', trim($data['pricepsft']));

                    if(is_numeric($price))
                    {
                        // if(trim($data['title']) == '1804-B'){
                        //     echo '<pre>';
                        //     print_r($data);exit;
                        // }

                        // $price = str_replace(',', '', $product->carea) * trim($data['pricepsft']);
                        // $product_price = $product->price + $price;
                        $product_price = str_replace(',', '', trim($data['totalamount'])); //str_replace(',', '', $product->carea) * $price;

                        // $down_amount = $product_price * ($product->projectname->dpayment / 100);
                        $down_amount = str_replace(',', '', trim($data['downpayment'])); //$product_price * ($product->dpaymentper/100);   // discounted_price = original_price * (discount / 100)

                        // $posession_amount = $product_price * ($product->projectname->pamount / 100);
                        $posession_amount = str_replace(',', '', trim($data['possessionamount'])); // * ($product->posperamount/100);
                        echo $data['title'].'--'.$product_price.'-'.$down_amount.'--'.$posession_amount.'<br>';
                        $remain_amount = $product_price - ( $down_amount + $posession_amount );


                        // $quater_installment = $remain_amount / $product->projectname->nofqtrly;
                        $quater_installment = $remain_amount / $product->numinstallment;

                        // $monthly_installment = $remain_amount / $product->projectname->nofmonthly;
                        $monthly_installment = $remain_amount / $product->nummoninstallment;

                        // echo $product->name.' Price ='.$product_price.'-- Downpayment 30% ='.$down_amount.'--- Pocession amount 10% ='.$posession_amount.'----Qtr Installment ='.$quater_installment.'====Monthly Installment = '.$monthly_installment;
                        // exit;

                        if($data['cornercharges'] > 0){
                            $product->corner = 1;
                            $product->corner_amt = str_replace(',', '', $data['cornercharges']);
                            // echo $data['cornercharges'];
                            // exit;
                        }

                        $product->carea = str_replace(',', '', $product->carea);
                        $product->price = $product_price;
                        $product->dpayment = $down_amount;
                        $product->posamount = $posession_amount;
                        $product->qtrinstallment = $quater_installment;
                        $product->moninstallment = $monthly_installment;
                        $product->psft = str_replace(',', '', trim($data['pricepsft']));
                        $product->save();
                        $counter++;
                    }
                    else
                    {
                        echo "Price is Not in Numaric Numbers";  echo "<br>";
                        echo 'Unit ID = '.trim($data['title']).' And Price = '.trim($data['pricepsft']);
                        exit;
                    }
                }

            }
        }

        echo 'Process End'; exit;

        // return redirect('inventory')->withStatus(__('Products Status Successfully Updated.'));
    }

    public function chooseCornerFile()
    {
        return view('choose_corner_file');
    }

    public function updateCorner(Request $request)
    {
        // echo "<pre>"; print_r($request->all()); exit;

        // Select File
        $path = $request->file('file')->getRealPath();

        // Geting file Record (head)
        $records = array_map('str_getcsv', file($path));
        // echo '<pre>';print_r($records[0]);exit;

        // Get field names from header column with lower letter
        $column_names = array_map('strtolower', preg_replace('/[^a-zA-Z0-9\']/', '', $records[0]));
        // echo '<pre>';print_r($column_names);exit;

        array_shift($records);

        foreach ($records as $record)
        {
            if (count($column_names) != count($record))
            {
                echo 'CSV Upload Invalid Data'; exit;
            }

            // Decode unwanted html entities
            $record = array_map("html_entity_decode", $record);

            // Set the field name as a key
            $record = array_combine($column_names, $record);
            // echo '<pre>';print_r($record);exit;

            // Get the clean data
            $rows[] = $this->clear_encoding_str($record);
        }

        // // Show All Data
        // echo '<pre>';print_r($rows);exit;

        foreach ($rows as $data)
        {
            // echo '<pre>';print_r($data['title']);exit;

            if(trim($data['title']))
            {
                $product = Product::where('unitid', trim($data['title']))->first();
                // echo "<pre>";print_r($product); exit;

                if($product)
                {
                    $product->corner = 1;
                    // $product->corner_amt = str_replace(',', '', trim($data['cornercharges']));
                    $product->save();
                }
            }
        }

        echo 'Process End'; exit;
        // return redirect('inventory')->withStatus(__('Products Status Successfully Updated.'));
    }

}


