<?php
namespace App\Console\Commands;
use Illuminate\Console\Command;
use App\Models\Product;
use Carbon\Carbon;
class FairyCron extends Command{
    protected $signature = 'fairy:cron';
    protected $description = 'Command description';

    public function __construct(){
        parent::__construct();
    }
    public function handle(){
        $products = Product::where('status', 'Hold')->where('hold_status', 1)->whereDate('hold_expiary', '<=', Carbon::now()->timezone('Asia/Karachi'))->get();
//        echo '<pre>';
//        print_r($products);
//        exit;
        foreach ($products as $product) {
            $product->hold_status = 0;
            $product->hold_expiary = '';
            $product->hold_by = 0;
            $product->status = 'Available';
            $product->save();
        }
        \Log::info("Product Cron is working fine!");
    }
}