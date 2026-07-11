<?php
namespace App\Console\Commands;

use App\Models\ProductNotes;
use App\Models\Target;
use Illuminate\Console\Command;
use Carbon\Carbon;

class TargetsCron extends Command
{
    protected $signature = 'targets:cron';
    protected $description = 'Targets Command Description';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {

        // $previous_targets = Target::whereBetween('start_date', [Carbon::now()->subMonth(1)->startOfMonth(), Carbon::now()->subMonth()->endOfMonth()])->get();

        // if(count($previous_targets) > 0)
        // {
        //     $startOfMonth=Carbon::now()->startOfMonth();
        //     $endOfMonth=Carbon::now()->endOfMonth();

        //     foreach($previous_targets as $previous_target)
        //     {
        //         $target = Target::where('user_id' , $previous_target->user_id)->whereBetween('start_date', [$startOfMonth, $endOfMonth])->first();

        //         if(!($target) || $target =='' )
        //         {
        //             Target::insert(
        //             [
        //                 'user_id'                           => $previous_target->user_id,

        //                 'set_sales_target'                  => $previous_target->set_sales_target,
        //                 'set_unit_target'                   => $previous_target->set_unit_target,
        //                 'set_lead_target'                   => $previous_target->set_lead_target,
        //                 'set_affiliator_target'             => $previous_target->set_affiliator_target,
        //                 'set_verified_calls_target'         => $previous_target->set_verified_calls_target,
        //                 'set_client_meetings_target'        => $previous_target->set_client_meetings_target,
        //                 'set_dealer_meetings_target'        => $previous_target->set_dealer_meetings_target,
        //                 'set_freelancer_meetings_target'    => $previous_target->set_freelancer_meetings_target,
        //                 'set_site_visit_target'             => $previous_target->set_site_visit_target,

        //                 'perday_sales_target'               =>  $previous_target->perday_sales_target ,
        //                 'perday_unit_target'                =>  $previous_target->perday_unit_target ,
        //                 'perday_lead_target'                =>  $previous_target->perday_lead_target ,
        //                 'perday_affiliator_target'          =>  $previous_target->perday_affiliator_target ,
        //                 'perday_verified_calls_target'      =>  $previous_target->perday_verified_calls_target ,
        //                 'perday_client_meetings_target'     =>  $previous_target->perday_client_meetings_target ,
        //                 'perday_dealer_meetings_target'     =>  $previous_target->perday_dealer_meetings_target ,
        //                 'perday_freelancer_meetings_target' =>  $previous_target->perday_freelancer_meetings_target ,
        //                 'perday_site_visit_target'          =>  $previous_target->perday_site_visit_target ,

        //                 'start_date' => Carbon::now(),
        //                 'expiry_date'=> $endOfMonth,

        //                 'created_at' => Carbon::now(),

        //             ]);
        //         }
        //         // else
        //         // {
        //         //     Target::where('id' , $target->id)->update(
        //         //     [
        //         //         'set_sales_target'                  => $previous_target->set_sales_target,
        //         //         'set_unit_target'                   => $previous_target->set_unit_target,
        //         //         'set_lead_target'                   => $previous_target->set_lead_target,
        //         //         'set_affiliator_target'             => $previous_target->set_affiliator_target,
        //         //         'set_verified_calls_target'         => $previous_target->set_verified_calls_target,
        //         //         'set_client_meetings_target'        => $previous_target->set_client_meetings_target,
        //         //         'set_dealer_meetings_target'        => $previous_target->set_dealer_meetings_target,
        //         //         'set_freelancer_meetings_target'    => $previous_target->set_freelancer_meetings_target,
        //         //         'set_site_visit_target'             => $previous_target->set_site_visit_target,

        //         //         'perday_sales_target'               =>  $previous_target->perday_sales_target ,
        //         //         'perday_unit_target'                =>  $previous_target->perday_unit_target ,
        //         //         'perday_lead_target'                =>  $previous_target->perday_lead_target ,
        //         //         'perday_affiliator_target'          =>  $previous_target->perday_affiliator_target ,
        //         //         'perday_verified_calls_target'      =>  $previous_target->perday_verified_calls_target ,
        //         //         'perday_client_meetings_target'     =>  $previous_target->perday_client_meetings_target ,
        //         //         'perday_dealer_meetings_target'     =>  $previous_target->perday_dealer_meetings_target ,
        //         //         'perday_freelancer_meetings_target' =>  $previous_target->perday_freelancer_meetings_target ,
        //         //         'perday_site_visit_target'          =>  $previous_target->perday_site_visit_target ,

        //         //         'start_date' => $previous_target->start_date,
        //         //         'expiry_date'=> $previous_target->expiry_date,

        //         //         'updated_at' => Carbon::now(),
        //         //     ]);
        //         // }
        //     }

        //     // echo"Targets Set Successfully for this Month"; exit;

        // }






        // // ProductNotes::insert([
        // //     'note' => 'Cron Testig',
        // //     'status' => 'Cron Testig',
        // //     'item_id' => 1,
        // //     'added_by' => 1,
        // // ]);


        \Log::info("Targets Cron is Working Fine...!");
    }
}
