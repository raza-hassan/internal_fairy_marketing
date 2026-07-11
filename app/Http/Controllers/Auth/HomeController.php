<?php



namespace App\Http\Controllers;

use App\Http\Helpers\Helper;
use Illuminate\Http\Request;
use App\Models\Leads;
use App\Models\Affiliator;
use App\Models\Clients;
use App\Models\Compain;
use App\Models\Project;
use App\Models\LeadSource;
use App\Models\Task;
use Carbon\Carbon;
use App\Models\AffiliatorTask;
use App\Models\Target;
use App\Models\Product;
use Carbon\CarbonPeriod;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;

class HomeController extends Controller {

    public function __construct() {

        $this->middleware('auth');

    }

    public function index()
    {
        $leads_count = Leads::where('user_id', Auth::user()->id)->count();
        $clients_count = Clients::where('user_id', Auth::user()->id)->count();

        $todayleads = Leads::whereDate('created_at', Carbon::today())->where('user_id', Auth::user()->id)->orderBy('id', 'desc')->get();
        $todayclients = Clients::whereDate('created_at', Carbon::today())->where('user_id', Auth::user()->id)->orderBy('id', 'desc')->get();
        $tasks = Task::where('added_by', Auth::user()->id)->where('deadline', date('Y-m-d'))->where('status', 0)->orderBy('id', 'desc')->limit(30)->get();

        $affiliator_count = Affiliator::where('user_id', Auth::user()->id)->count();
        $aaffiliator_count = Affiliator::where('user_id', Auth::user()->id)->where('status', 1)->count();

        $projects = Project::orderBy('id', 'ASC')->get();
        $sources = LeadSource::all();

	    if (Auth::user()->role == 10)
        {
            $compains=Compain::all();
            return view('compain.index' , compact('compains'));
        }

        // ==================Graphp for Today ==================

        // ====== Users With Helper======
            $data = array(
                'id' => Auth::user()->id,
                'role' => Auth::user()->role,
            );
            $responce = Helper::users($data);
            $graph_users = $responce['users'];
            $account = $responce['account'];
        // ====== Users With Helper======

            $id = Auth::user()->id ;

            // Today's Record
            $record = Target::where('user_id', $id)->whereMonth('start_date', Carbon::now()->month)->whereYear('start_date', Carbon::now()->year)->get();
            // $achive_verified_calls_target = Task::where('added_by', $id)->where('type', 'Calls')->where('subtype', 'Contacted Client')->whereDate('created_at', Carbon::today())->count();

            $achive_verified_calls_target=Task::where('added_by', $id) // First where clause
                                        ->where('type', 'Calls')
                                        ->where(function ($query)
                                        {
                                            $query->where('subtype', 'Contacted Client')
                                                ->orWhere('subtype', 'Whatsapp Call');
                                        })
                                        ->whereDate('created_at', Carbon::today())
                                        ->count();

            $achive_dealer_meetings_target=AffiliatorTask::join('affiliators', 'affiliators.id', '=', 'affiliatortask.affliator_id')
                                                ->where('affiliatortask.user_id', $id)
                                                ->where('affiliatortask.type', '=', 'Meetings')
                                                ->where('affiliatortask.subtype', '=', 'Meeting (Done)')
                                                ->whereDate('affiliatortask.created_at', Carbon::today())
                                                ->where('affiliators.type', '=', 'Dealer')
                                                // ->get(['affiliatortask.*']);
                                                ->count();

            $achive_freelancer_meetings_target=AffiliatorTask::join('affiliators', 'affiliators.id', '=', 'affiliatortask.affliator_id')
                                                ->where('affiliatortask.user_id', $id)
                                                ->where('affiliatortask.type', '=', 'Meetings')
                                                ->where('affiliatortask.subtype', '=', 'Meeting (Done)')
                                                ->whereDate('affiliatortask.created_at', Carbon::today())
                                                ->where('affiliators.type', '=', 'Freelancer')
                                                ->count();


            // // Today's
            // $achive_client_meetings_target= Task::where('added_by', $id)->where('type', 'Meetings')->where('subtype', 'Meeting (Done)')->whereDate('created_at', Carbon::today())->count();
            // $achive_site_visit_target= Task::where('added_by', $id)->where('type', 'Meetings')->where('subtype', 'Meeting (Done)')->where('location', 'Site Office')->whereDate('created_at', Carbon::today())->count();

            // Weekly
            $achive_client_meetings_target= Task::where('added_by', $id)->where('type', 'Meetings')->where('subtype', 'Meeting (Done)')->whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])->count();
            $achive_site_visit_target= Task::where('added_by', $id)->where('type', 'Meetings')->where('subtype', 'Meeting (Done)')->where('location', 'Site Office')->whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])->count();


            $achive_unit_target_query = Product::join('leads', 'leads.item_id', '=', 'product.unitid')
                                                ->where('product.status', '=', 'Sold')
                                                ->whereMonth('product.sold_at', Carbon::now()->month)
                                                ->whereYear('product.sold_at', Carbon::now()->year)
                                                ->where('leads.user_id', $id)
                                                ->select('product.unitid')
                                                ->distinct();
            // Get the unique unit IDs
            $unitids = (clone $achive_unit_target_query)->pluck('product.unitid');
            // Sum the price of those unique unitids
            $achive_sales_target = Product::whereIn('unitid', $unitids)->sum('price');
            // Imploded unit IDs for passing in URL
            $achive_unit_target_unitids = $unitids->implode(',');
            // Count how many unique unitids there are
            $achive_unit_target = (clone $achive_unit_target_query)->count('product.unitid');

            $set_sales_target               = 0;
            $set_unit_target                = 0;
            $set_verified_calls_target      = 0;
            $set_client_meetings_target     = 0;
            $set_dealer_meetings_target     = 0;
            $set_freelancer_meetings_target = 0;
            $set_site_visit_target          = 0;

            $set_contacted_clients_target      = 0;
            $set_leads_target                  = 0;
            $set_affiliator_target             = 0;

            $today_set_client_meetings_target  = 0;
            $today_achive_client_meetings_target  = 0;

            // $today_set_verified_calls_target   = 0;
            // $today_achive_contacted_clients_target= 0;


            // Weekly Client Meetings Target And Site Visit Target
            $week_start= Carbon::now()->startOfWeek();
            $week_end  = Carbon::now()->endOfWeek();

            $month_start= date('m',strtotime($week_start));
            $month_end  = date('m',strtotime($week_end));
            $days_count = 0;

            if($month_start == $month_end)
            {
                $period = CarbonPeriod::create($week_start, $week_end);

                foreach ($period as $date)
                {
                    if ($date->dayOfWeek !== Carbon::SUNDAY)
                    {
                        $days_count++;
                    }
                }
            }else{

                $lastWeekStart = Carbon::now()->subMonth()->endOfMonth()->startOfWeek();
                $lastWeekEnd = Carbon::now()->subMonth()->endOfMonth();

                $period = CarbonPeriod::create($lastWeekStart, $lastWeekEnd);

                foreach ($period as $date)
                {
                    if ($date->dayOfWeek !== Carbon::SUNDAY)
                    {
                        $days_count++;
                    }
                }
            }

            // echo '<pre>';print_r($days_count); exit;

            if( count($record) > 0)
            {
                $set_sales_target               = $record->sum('set_sales_target');
                $set_unit_target                = $record->sum('set_unit_target');
                // $set_sales_target               = $record->sum('perday_sales_target');
                // $set_unit_target                = $record->sum('perday_unit_target');
                $set_verified_calls_target      = $record->sum('perday_verified_calls_target');
                $set_dealer_meetings_target     = $record->sum('perday_dealer_meetings_target');
                $set_freelancer_meetings_target = $record->sum('perday_freelancer_meetings_target');

                // Today's
                // $set_client_meetings_target     = $record->sum('perday_client_meetings_target');
                // $set_site_visit_target          = $record->sum('perday_site_visit_target') ;

                // weekly
                $set_client_meetings_target = $record->sum('perday_client_meetings_target') * $days_count;
                $set_site_visit_target = $record->sum('perday_site_visit_target')  * $days_count;

                $set_leads_target                   = $record->sum('perday_lead_target');
                $set_affiliator_target              = $record->sum('perday_affiliator_target');
                $today_set_verified_calls_target    = $record->sum('perday_verified_calls_target');
                $today_set_client_meetings_target   = $record->sum('perday_client_meetings_target');
            }


            // echo $today_set_client_meetings_target;exit;


            // ------Lead Graph--------
                $facebook_leads  = Leads::where('source_id', 3 )->where('user_id', $id )->whereDate('created_at', Carbon::today())->count();
                $uan             = Leads::where('source_id', 10)->where('user_id', $id )->whereDate('created_at', Carbon::today())->count();
                $personal_leads  = Leads::where('source_id', 11)->where('user_id', $id )->whereDate('created_at', Carbon::today())->count();
                $dealer_leads    = Leads::where('source_id', 12)->where('user_id', $id )->whereDate('created_at', Carbon::today())->count();
                $freelancer_leads= Leads::where('source_id', 13)->where('user_id', $id )->whereDate('created_at', Carbon::today())->count();
                $website_leads   = Leads::where('source_id', 14)->where('user_id', $id )->whereDate('created_at', Carbon::today())->count();
            // ------Lead Graph---------



            // ======================== Boxes Record =================
                // $achive_number_of_token_target  = Product::where('hold_by', $id)->where('status', 'Token')->whereDate('sold_at', Carbon::today())->count();
                $achive_number_of_token_target = Product::where('hold_by', $id)
                ->where(function($query) {
                    $query->where('status', 'Token')
                        ->orWhere('status', 'Partial Token');
                })
                ->whereMonth('sold_at', Carbon::now()->month)->whereYear('sold_at', Carbon::now()->year)->count();


                // // Check Where Condition with Or-Condition
                $achive_contacted_clients_target=Task::where('added_by', $id) // First where clause
                                ->where('type', 'Calls')
                                ->where(function ($query)
                                {
                                    $query->where('subtype', 'Contacted Client')
                                        ->orWhere('subtype', 'Whatsapp Call');
                                })
                                ->whereDate('created_at', Carbon::today())
                                ->count();

                // $today_achive_contacted_clients_target=Task::where('added_by', $id) // First where clause
                //                 ->where('type', 'Calls')
                //                 ->where(function ($query)
                //                 {
                //                     $query->where('subtype', 'Contacted Client')
                //                         ->orWhere('subtype', 'Whatsapp Call');
                //                 })
                //                 ->whereDate('created_at', Carbon::today())
                //                 ->count();

                $today_achive_client_meetings_target= Task::where('added_by', $id)->where('type', 'Meetings')->where('subtype', 'Meeting (Done)')->whereDate('created_at', Carbon::today())->count();

                $achive_new_leads_target = Leads::where('user_id', $id)->whereDate('created_at', Carbon::today())->count();

                $achive_affiliator_target=Affiliator::where('user_id', $id)
                            ->where('status', 1)
                            ->whereDate('created_at', Carbon::today())
                            ->count();

                $dealer=Affiliator::where('user_id', $id)
                            ->where('status', 1)
                            ->where('type', '=', 'Dealer')
                            ->whereDate('created_at', Carbon::today())
                            ->count();

                $freelancer=Affiliator::where('user_id', $id)
                            ->where('status', 1)
                            ->where('type', '=', 'Freelancer')
                            ->whereDate('created_at', Carbon::today())
                            ->count();


            // ======================== Boxes Record =================


            // // ========================Overall Record =================


            //     $overall_record = Target::where('user_id', $id)->get();

            //     $overall_set_sales_target               =  $overall_record->sum('set_sales_target');
            //     $overall_set_unit_target                =  $overall_record->sum('set_unit_target');
            //     $overall_set_lead_target                =  $overall_record->sum('set_lead_target');
            //     $overall_set_affiliator_target          =  $overall_record->sum('set_affiliator_target');
            //     $overall_set_verified_calls_target      =  $overall_record->sum('set_verified_calls_target');
            //     $overall_set_client_meetings_target     =  $overall_record->sum('set_client_meetings_target');
            //     $overall_set_dealer_meetings_target     =  $overall_record->sum('set_dealer_meetings_target');
            //     $overall_set_freelancer_meetings_target =  $overall_record->sum('set_freelancer_meetings_target');
            //     $overall_set_site_visit_target          =  $overall_record->sum('set_site_visit_target');


            //     //  overall Achive

            //     $overall_achive_sales_target = Product::join('leads', 'leads.item_id', '=', 'product.unitid')
            //                                     ->where('product.status', '=', 'Sold')
            //                                     ->where('leads.user_id', '=', $id)
            //                                     ->sum('price');


            //     $overall_achive_unit_target=Product::join('leads', 'leads.item_id', '=', 'product.unitid')
            //                                 ->where('product.status', '=', 'Sold')
            //                                 ->where('leads.user_id', '=', $id)
            //                                 ->count();

            //     $overall_achive_leads_target = Leads::where('user_id', $id)->count();

            //     $overall_achive_contacted_clients_target=Task::where('added_by', $id) // First where clause
            //                     ->where('type', 'Calls')
            //                     ->where(function ($query)
            //                     {
            //                         $query->where('subtype', 'Contacted Client')
            //                             ->orWhere('subtype', 'Whatsapp Call');
            //                     })
            //                     ->count();



            //     $overall_achive_client_meetings_target  = Task::where('added_by', $id)->where('type', 'Meetings')->where('subtype', 'Meeting (Done)')->count();

            //     // echo '<pre>';print_r($overall_record->sum('set_unit_target')); exit;

            // // ========================Overall Record =================


            $user_target = array();

            $user_target =[
                'set_sales_target'                  => $set_sales_target,
                'set_unit_target'                   => $set_unit_target,
                'set_verified_calls_target'         => $set_verified_calls_target,
                'set_client_meetings_target'        => $set_client_meetings_target,
                // 'set_dealer_meetings_target'        => $set_dealer_meetings_target,
                // 'set_freelancer_meetings_target'    => $set_freelancer_meetings_target,
                'set_site_visit_target'             => $set_site_visit_target,

                'achive_sales_target'               => $achive_sales_target,
                'achive_unit_target'                => $achive_unit_target,
                'achive_unit_target_unitids'        => $achive_unit_target_unitids,
                'achive_verified_calls_target'      => $achive_verified_calls_target,
                'achive_client_meetings_target'     => $achive_client_meetings_target,
                // 'achive_dealer_meetings_target'     => $achive_dealer_meetings_target,
                // 'achive_freelancer_meetings_target' => $achive_freelancer_meetings_target,
                'achive_site_visit_target'          => $achive_site_visit_target,

                // Leads Graph
                'facebook_leads'    => $facebook_leads,
                'dealer_leads'      => $dealer_leads,
                'freelancer_leads'  => $freelancer_leads,
                'personal_leads'    => $personal_leads,
                'uan'               => $uan,
                'website_leads'     => $website_leads,


                //  Token
                'achive_number_of_token_target'   => $achive_number_of_token_target,

                // // today record
                // 'today_set_verified_calls_target' => $today_set_verified_calls_target,
                // 'today_set_client_meetings_target' => $today_set_client_meetings_target,

                // 'today_achive_contacted_clients_target' => $today_achive_contacted_clients_target,
                // 'today_achive_client_meetings_target'   => $today_achive_client_meetings_target,

                // New Leads
                'set_leads_target'                => $set_leads_target,
                'achive_new_leads_target'         => $achive_new_leads_target,

                // dealer
                'set_dealer_meetings_target'      => $set_dealer_meetings_target,
                'achive_dealer_meetings_target'   => $achive_dealer_meetings_target,
                // freelancer
                'set_freelancer_meetings_target'    => $set_freelancer_meetings_target,
                'achive_freelancer_meetings_target' => $achive_freelancer_meetings_target,
                // affiliator
                'set_affiliator_target'    => $set_affiliator_target,
                'achive_affiliator_target' => $achive_affiliator_target,
                'dealer'                   => $dealer,
                'freelancer'               => $freelancer,


                // // overall record of company
                // 'overall_set_sales_target'                  =>  $overall_set_sales_target ,
                // 'overall_set_unit_target'                   =>  $overall_set_unit_target ,
                // 'overall_set_lead_target'                   =>  $overall_set_lead_target ,
                // 'overall_set_affiliator_target'             =>  $overall_set_affiliator_target ,
                // 'overall_set_verified_calls_target'         =>  $overall_set_verified_calls_target ,
                // 'overall_set_client_meetings_target'        =>  $overall_set_client_meetings_target ,
                // 'overall_set_dealer_meetings_target'        =>  $overall_set_dealer_meetings_target ,
                // 'overall_set_freelancer_meetings_target'    =>  $overall_set_freelancer_meetings_target ,
                // 'overall_set_site_visit_target'             =>  $overall_set_site_visit_target ,

                // // Overall Achive
                // 'overall_achive_sales_target'               =>  $overall_achive_sales_target,
                // 'overall_achive_unit_target'                =>  $overall_achive_unit_target,
                // 'overall_achive_leads_target'               =>  $overall_achive_leads_target,
                // 'overall_achive_contacted_clients_target'   =>  $overall_achive_contacted_clients_target,
                // 'overall_achive_client_meetings_target'     =>  $overall_achive_client_meetings_target ,

            ];


        // =====================End Graph ======================

        // $data  = Leads::all()->unique('source_id');
        // foreach($data as $da){
        //     if($da->leadSource){
        //         echo "<br>"; print_r($da->leadSource->type); echo " ..........Lead Id ". $da['id'];
        //     }else{
        //         echo "<br>";echo " Source ID Missing Lead Id = ". $da->id ;
        //     }
        // }exit;


        // return view("users.user_graph", compact('users' , 'user_target' ));
        return view('home', compact('graph_users' , 'user_target' , 'leads_count', 'clients_count', 'affiliator_count', 'aaffiliator_count', 'todayleads', 'todayclients', 'projects', 'sources', 'account','tasks'));
    }

    public function UserSortingGraph(Request $request)
    {

        // echo '<pre>'; print_r($request->all());
        // exit;


        // $for_sale_and_unit = Target::whereIn('user_id', $ids)->whereYear('start_date', Carbon::now()->format('Y'))->first();
        // echo '<pre>'; print_r($for_sale_and_unit);
        // exit;
        // echo '<pre>'; print_r(Carbon::now()->subMonth()->endOfMonth());exit;

        // =========Dashboard Data ===========
            $leads_count = Leads::where('user_id', Auth::user()->id)->count();
            $clients_count = Clients::where('user_id', Auth::user()->id)->count();
            $todayleads = Leads::whereDate('created_at', Carbon::today())->where('user_id', Auth::user()->id)->orderBy('id', 'desc')->get();
            $todayclients = Clients::whereDate('created_at', Carbon::today())->where('user_id', Auth::user()->id)->orderBy('id', 'desc')->get();
            $tasks = Task::where('added_by', Auth::user()->id)->where('deadline', date('Y-m-d'))->where('status', 0)->orderBy('id', 'desc')->limit(30)->get();
            $affiliator_count = Affiliator::where('user_id', Auth::user()->id)->count();
            $aaffiliator_count = Affiliator::where('user_id', Auth::user()->id)->where('status', 1)->count();
            $projects = Project::orderBy('id', 'ASC')->get();
            $sources = LeadSource::all();

            if (Auth::user()->role == 10){
                $compains=Compain::all();
                return view('compain.index' , compact('compains'));
            }
        // ===================================

        $user_ids = $request->input('users', [Auth::user()->id]);

        // Check if 'all_active_user' is in the $user_ids array
        if (in_array('all_active_user', $user_ids))
        {
            // ===== Users With Helper=====
                $data = array(
                    'id' => Auth::user()->id,
                    'role' => Auth::user()->role,
                );
                $responce = Helper::users($data);
                $users = $responce['users'];
                $user_ids = $users->pluck('id');
            // ===== Users With Helper=====
        }

        $time_period = $request->input('time_period');

        $set_sales_target               = 0;
        $set_unit_target                = 0;
        $set_verified_calls_target      = 0;
        $set_client_meetings_target     = 0;
        $set_dealer_meetings_target     = 0;
        $set_freelancer_meetings_target = 0;
        $set_site_visit_target          = 0;

        $set_contacted_clients_target      = 0;
        $set_leads_target                  = 0;
        $set_affiliator_target             = 0;

        // $today_set_client_meetings_target  = 0;
        // $today_achive_client_meetings_target  = 0;

        // $today_set_verified_calls_target   = 0;
        // $today_achive_contacted_clients_target= 0;


        // $for_sale_and_unit = Target::where('user_id', $id)->whereYear('start_date', Carbon::now()->format('Y'))->get();


        if($request->time_period == 'custom_date' && $request->startDate != '' && $request->endDate != '')
        {
            // $startDate= date("Y-m-d", strtotime($request->startDate)). ' 00:00:00';
            // $endDate =  date("Y-m-d", strtotime($request->endDate)) . ' 23:59:59';

            $startDate = Carbon::create(date("Y-m-d", strtotime($request->startDate)). ' 00:00:00');
            $endDate   = Carbon::create(date("Y-m-d", strtotime($request->endDate)) . ' 23:59:59');

            $start = strtotime($startDate);
            $end = strtotime($endDate);

            if($start > $end) // if user enter start_Date into end_Date and end_Date into Start_Date
            {
                return redirect('/')->withErrors(__('Wrong Selection Of Date. Start Date Will Not Be Grater Than End Date.!'));
            }

            if($end > $start)
            {
                // $datediff = $end - $start;
                // $days_count= round($datediff / (60 * 60 * 24));

                $period = CarbonPeriod::create($startDate, $endDate);
                $days_btwn_dates = 0;
                foreach ($period as $date)
                {
                    if ($date->dayOfWeek !== Carbon::SUNDAY)
                    {
                        $days_btwn_dates++;
                    }
                }
            }


            // $start_month = date('m Y',strtotime($startDate));
            // $end_month = date('m Y',strtotime($endDate));
            // // $days_in_month=Carbon::now()->month($start_month)->daysInMonth;

            $startOfMonth= $startDate->startOfMonth();
            $endOfMonth  = $endDate->endOfMonth();


            $period = CarbonPeriod::create($startOfMonth, $endOfMonth);
            $total_days = 0;
            foreach ($period as $date)
            {
                if ($date->dayOfWeek !== Carbon::SUNDAY)
                {
                    $total_days++;
                }
            }

            // echo '<pre>'; print_r($days_btwn_dates);
            // echo '<pre>'; print_r($total_days);
            // exit;

            // use again Because above startOfMonth and endOfMonth chnage the dates
            $startDate = date("Y-m-d", strtotime($request->startDate)). ' 00:00:00';
            $endDate   = date("Y-m-d", strtotime($request->endDate)) . ' 23:59:59';


            $record = Target::whereIn('user_id', $user_ids)->whereBetween('start_date', [$startOfMonth , $endOfMonth])->get();
            $achive_verified_calls_target=Task::whereIn('added_by', $user_ids) // First where clause
                                            ->where('type', 'Calls')
                                            ->where(function ($query)
                                            {
                                                $query->where('subtype', 'Contacted Client')
                                                    ->orWhere('subtype', 'Whatsapp Call');
                                            })
                                            ->whereDate('created_at', '>=', $startDate)
                                            ->whereDate('created_at', '<=', $endDate)
                                            ->count();

            $achive_client_meetings_target  = Task::whereIn('added_by', $user_ids)->where('type', 'Meetings')->where('subtype', 'Meeting (Done)')->whereDate('created_at', '>=', $startDate)->whereDate('created_at', '<=', $endDate)->count();


            $achive_dealer_meetings_target  =AffiliatorTask::join('affiliators', 'affiliators.id', '=', 'affiliatortask.affliator_id')
                                                ->whereIn('affiliatortask.user_id', $user_ids)
                                                ->where('affiliatortask.type', '=', 'Meetings')
                                                ->where('affiliatortask.subtype', '=', 'Meeting (Done)')
                                                ->whereDate('affiliatortask.created_at', '>=', $startDate)
                                                ->whereDate('affiliatortask.created_at', '<=', $endDate)
                                                ->where('affiliators.type', '=', 'Dealer')
                                                ->count();

            $achive_freelancer_meetings_target=AffiliatorTask::join('affiliators', 'affiliators.id', '=', 'affiliatortask.affliator_id')
                                                ->whereIn('affiliatortask.user_id', $user_ids)
                                                ->where('affiliatortask.type', '=', 'Meetings')
                                                ->where('affiliatortask.subtype', '=', 'Meeting (Done)')
                                                ->whereDate('affiliatortask.created_at', '>=', $startDate)
                                                ->whereDate('affiliatortask.created_at', '<=', $endDate)
                                                ->where('affiliators.type', '=', 'Freelancer')
                                                ->count();

            $achive_site_visit_target       = Task::whereIn('added_by', $user_ids)->where('type', 'Meetings')->where('subtype', 'Meeting (Done)')->where('location', 'Site Office')->whereDate('created_at', '>=', $startDate)->whereDate('created_at', '<=', $endDate)->count();

            $achive_unit_target_query = Product::join('leads', 'leads.item_id', '=', 'product.unitid')
                                                ->where('product.status', '=', 'Sold')
                                                ->whereDate('product.sold_at', '>=', $startDate)
                                                ->whereDate('product.sold_at', '<=', $endDate)
                                                ->whereIn('leads.user_id', $user_ids)
                                                ->select('product.unitid')
                                                ->distinct();
            // Get the unique unit IDs
            $unitids = (clone $achive_unit_target_query)->pluck('product.unitid');
            // Sum the price of those unique unitids
            $achive_sales_target = Product::whereIn('unitid', $unitids)->sum('price');
            // Imploded unit IDs for passing in URL
            $achive_unit_target_unitids = $unitids->implode(',');
            // Count how many unique unitids there are
            $achive_unit_target = (clone $achive_unit_target_query)->count('product.unitid');

            //  Leads Graph

            // ------Lead Graph--------
                $facebook_leads  = Leads::where('source_id', 3 )->whereIn('user_id', $user_ids)->whereDate('created_at', '>=', $startDate)->whereDate('created_at', '<=', $endDate)->count();
                $uan             = Leads::where('source_id', 10)->whereIn('user_id', $user_ids)->whereDate('created_at', '>=', $startDate)->whereDate('created_at', '<=', $endDate)->count();
                $personal_leads  = Leads::where('source_id', 11)->whereIn('user_id', $user_ids)->whereDate('created_at', '>=', $startDate)->whereDate('created_at', '<=', $endDate)->count();
                $dealer_leads    = Leads::where('source_id', 12)->whereIn('user_id', $user_ids)->whereDate('created_at', '>=', $startDate)->whereDate('created_at', '<=', $endDate)->count();
                $freelancer_leads= Leads::where('source_id', 13)->whereIn('user_id', $user_ids)->whereDate('created_at', '>=', $startDate)->whereDate('created_at', '<=', $endDate)->count();
                $website_leads   = Leads::where('source_id', 14)->whereIn('user_id', $user_ids)->whereDate('created_at', '>=', $startDate)->whereDate('created_at', '<=', $endDate)->count();
            // ------Lead Graph---------


            // ======================== Boxes Record =================
                // $achive_number_of_token_target  = Product::where('hold_by', $id)->where('status', 'Token')->whereDate('sold_at', '>=', $startDate)->whereDate('sold_at', '<=', $endDate)->count();
                $achive_number_of_token_target = Product::whereIn('hold_by', $user_ids)
                ->where(function($query) {
                    $query->where('status', 'Token')
                        ->orWhere('status', 'Partial Token');
                })
                ->whereDate('sold_at', '>=', $startDate)->whereDate('sold_at', '<=', $endDate)->count();



                // // Check Where Condition with Or-Condition
                $achive_contacted_clients_target=Task::whereIn('added_by', $user_ids) // First where clause
                                ->where('type', 'Calls')
                                ->where(function ($query)
                                {
                                    $query->where('subtype', 'Contacted Client')
                                        ->orWhere('subtype', 'Whatsapp Call');
                                })
                                ->whereDate('created_at', '>=', $startDate)->whereDate('created_at', '<=', $endDate)
                                ->count();


                // $today_achive_contacted_clients_target=Task::whereIn('added_by', $user_ids) // First where clause
                //                 ->where('type', 'Calls')
                //                 ->where(function ($query)
                //                 {
                //                     $query->where('subtype', 'Contacted Client')
                //                         ->orWhere('subtype', 'Whatsapp Call');
                //                 })
                //                 ->whereDate('created_at', Carbon::today())
                //                 ->count();

                // $today_achive_client_meetings_target= Task::whereIn('added_by', $user_ids)->where('type', 'Meetings')->where('subtype', 'Meeting (Done)')->whereDate('created_at', Carbon::today())->count();

                $achive_new_leads_target = Leads::whereIn('user_id', $user_ids)->whereDate('created_at', '>=', $startDate)->whereDate('created_at', '<=', $endDate)->count();

                $achive_affiliator_target=Affiliator::whereIn('user_id', $user_ids)
                            ->where('status', 1)
                            ->whereDate('created_at', '>=', $startDate)->whereDate('created_at', '<=', $endDate)
                            ->count();

                $dealer=Affiliator::whereIn('user_id', $user_ids)
                            ->where('status', 1)
                            ->where('type', '=', 'Dealer')
                            ->whereDate('created_at', '>=', $startDate)->whereDate('created_at', '<=', $endDate)
                            ->count();

                $freelancer=Affiliator::whereIn('user_id', $user_ids)
                            ->where('status', 1)
                            ->where('type', '=', 'Freelancer')
                            ->whereDate('created_at', '>=', $startDate)->whereDate('created_at', '<=', $endDate)
                            ->count();
            // ======================== Boxes Record =================

        }

        if ($time_period == 'today')
        {
            // current Month
            $record = Target::whereIn('user_id', $user_ids)->whereMonth('start_date', Carbon::now()->month)->whereYear('start_date', Carbon::now()->year)->get();

            // $achive_verified_calls_target = Task::where('added_by', $id)->where('type', 'Calls')->where('subtype', 'Contacted Client')->whereDate('created_at', Carbon::today())->count();

            $achive_verified_calls_target=Task::whereIn('added_by', $user_ids) // First where clause
                                            ->where('type', 'Calls')
                                            ->where(function ($query)
                                            {
                                                $query->where('subtype', 'Contacted Client')
                                                    ->orWhere('subtype', 'Whatsapp Call');
                                            })
                                            ->whereDate('created_at', Carbon::today())
                                            ->count();

            $achive_client_meetings_target= Task::whereIn('added_by', $user_ids)->where('type', 'Meetings')->where('subtype', 'Meeting (Done)')->whereDate('created_at', Carbon::today())->count();

            $achive_dealer_meetings_target=AffiliatorTask::join('affiliators', 'affiliators.id', '=', 'affiliatortask.affliator_id')
                                                ->whereIn('affiliatortask.user_id', $user_ids)
                                                ->where('affiliatortask.type', '=', 'Meetings')
                                                ->where('affiliatortask.subtype', '=', 'Meeting (Done)')
                                                ->whereDate('affiliatortask.created_at', Carbon::today())
                                                ->where('affiliators.type', '=', 'Dealer')
                                                // ->get(['affiliatortask.*']);
                                                ->count();

            $achive_freelancer_meetings_target=AffiliatorTask::join('affiliators', 'affiliators.id', '=', 'affiliatortask.affliator_id')
                                                ->whereIn('affiliatortask.user_id', $user_ids)
                                                ->where('affiliatortask.type', '=', 'Meetings')
                                                ->where('affiliatortask.subtype', '=', 'Meeting (Done)')
                                                ->whereDate('affiliatortask.created_at', Carbon::today())
                                                ->where('affiliators.type', '=', 'Freelancer')
                                                ->count();

            $achive_site_visit_target= Task::whereIn('added_by', $user_ids)->where('type', 'Meetings')->where('subtype', 'Meeting (Done)')->where('location', 'Site Office')->whereDate('created_at', Carbon::today())->count();

            $achive_unit_target_query = Product::join('leads', 'leads.item_id', '=', 'product.unitid')
                                    ->where('product.status', '=', 'Sold')
                                    ->whereDate('product.sold_at', Carbon::today())
                                    ->whereIn('leads.user_id', $user_ids)
                                    ->select('product.unitid')
                                    ->distinct();
            // Get the unique unit IDs
            $unitids = (clone $achive_unit_target_query)->pluck('product.unitid');
            // Sum the price of those unique unitids
            $achive_sales_target = Product::whereIn('unitid', $unitids)->sum('price');
            // Imploded unit IDs for passing in URL
            $achive_unit_target_unitids = $unitids->implode(',');
            // Count how many unique unitids there are
            $achive_unit_target = (clone $achive_unit_target_query)->count('product.unitid');

            // ------Lead Graph--------
                $facebook_leads  = Leads::where('source_id', 3 )->whereIn('user_id', $user_ids)->whereDate('created_at', Carbon::today())->count();
                $uan             = Leads::where('source_id', 10)->whereIn('user_id', $user_ids)->whereDate('created_at', Carbon::today())->count();
                $personal_leads  = Leads::where('source_id', 11)->whereIn('user_id', $user_ids)->whereDate('created_at', Carbon::today())->count();
                $dealer_leads    = Leads::where('source_id', 12)->whereIn('user_id', $user_ids)->whereDate('created_at', Carbon::today())->count();
                $freelancer_leads= Leads::where('source_id', 13)->whereIn('user_id', $user_ids)->whereDate('created_at', Carbon::today())->count();
                $website_leads   = Leads::where('source_id', 14)->whereIn('user_id', $user_ids)->whereDate('created_at', Carbon::today())->count();
            // ------Lead Graph---------



            // ======================== Boxes Record =================
                // $achive_number_of_token_target  = Product::where('hold_by', $id)->where('status', 'Token')->whereDate('sold_at', Carbon::today())->count();
                $achive_number_of_token_target = Product::whereIn('hold_by', $user_ids)
                ->where(function($query) {
                    $query->where('status', 'Token')
                        ->orWhere('status', 'Partial Token');
                })
                ->whereDate('sold_at', Carbon::today())->count();

                // // Check Where Condition with Or-Condition
                $achive_contacted_clients_target=Task::whereIn('added_by', $user_ids) // First where clause
                                ->where('type', 'Calls')
                                ->where(function ($query)
                                {
                                    $query->where('subtype', 'Contacted Client')
                                        ->orWhere('subtype', 'Whatsapp Call');
                                })
                                ->whereDate('created_at', Carbon::today())
                                ->count();

                // $today_achive_contacted_clients_target=Task::whereIn('added_by', $user_ids) // First where clause
                //                 ->where('type', 'Calls')
                //                 ->where(function ($query)
                //                 {
                //                     $query->where('subtype', 'Contacted Client')
                //                         ->orWhere('subtype', 'Whatsapp Call');
                //                 })
                //                 ->whereDate('created_at', Carbon::today())
                //                 ->count();


                // $today_achive_client_meetings_target= Task::whereIn('added_by', $user_ids)->where('type', 'Meetings')->where('subtype', 'Meeting (Done)')->whereDate('created_at', Carbon::today())->count();


                $achive_new_leads_target = Leads::whereIn('user_id', $user_ids)->whereDate('created_at', Carbon::today())->count();

                $achive_affiliator_target=Affiliator::whereIn('user_id', $user_ids)
                            ->where('status', 1)
                            ->whereDate('created_at', Carbon::today())
                            ->count();

                $dealer=Affiliator::whereIn('user_id', $user_ids)
                            ->where('status', 1)
                            ->where('type', '=', 'Dealer')
                            ->whereDate('created_at', Carbon::today())
                            ->count();

                $freelancer=Affiliator::whereIn('user_id', $user_ids)
                            ->where('status', 1)
                            ->where('type', '=', 'Freelancer')
                            ->whereDate('created_at', Carbon::today())
                            ->count();


            // ======================== Boxes Record =================

        }

        if($time_period == 'this_week')
        {
            // This Week
            $record = Target::whereIn('user_id', $user_ids)->whereMonth('start_date', Carbon::now()->month)->whereYear('start_date', Carbon::now()->format('Y'))->get();
            // $achive_verified_calls_target = Task::where('added_by', $id)->where('type', 'Calls')->where('subtype', 'Contacted Client')->whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])->count();

            $achive_verified_calls_target=Task::whereIn('added_by', $user_ids) // First where clause
                                            ->where('type', 'Calls')
                                            ->where(function ($query)
                                            {
                                                $query->where('subtype', 'Contacted Client')
                                                    ->orWhere('subtype', 'Whatsapp Call');
                                            })
                                            ->whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])
                                            ->count();

            $achive_client_meetings_target= Task::whereIn('added_by', $user_ids)->where('type', 'Meetings')->where('subtype', 'Meeting (Done)')->whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])->count();
            $achive_dealer_meetings_target=AffiliatorTask::join('affiliators', 'affiliators.id', '=', 'affiliatortask.affliator_id')
                                                ->whereIn('affiliatortask.user_id', $user_ids)
                                                ->where('affiliatortask.type', '=', 'Meetings')
                                                ->where('affiliatortask.subtype', '=', 'Meeting (Done)')
                                                ->whereBetween('affiliatortask.created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])
                                                ->where('affiliators.type', '=', 'Dealer')
                                                // ->get(['affiliatortask.*']);
                                                ->count();

            $achive_freelancer_meetings_target=AffiliatorTask::join('affiliators', 'affiliators.id', '=', 'affiliatortask.affliator_id')
                                                ->whereIn('affiliatortask.user_id', $user_ids)
                                                ->where('affiliatortask.type', '=', 'Meetings')
                                                ->where('affiliatortask.subtype', '=', 'Meeting (Done)')
                                                ->whereBetween('affiliatortask.created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])
                                                ->where('affiliators.type', '=', 'Freelancer')
                                                ->count();
            $achive_site_visit_target= Task::whereIn('added_by', $user_ids)->where('type', 'Meetings')->where('subtype', 'Meeting (Done)')->where('location', 'Site Office')->whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])->count();

            $achive_unit_target_query = Product::join('leads', 'leads.item_id', '=', 'product.unitid')
                                    ->where('product.status', '=', 'Sold')
                                    ->whereBetween('product.sold_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])
                                    ->whereIn('leads.user_id', $user_ids)
                                    ->select('product.unitid')
                                    ->distinct();
            // Get the unique unit IDs
            $unitids = (clone $achive_unit_target_query)->pluck('product.unitid');
            // Sum the price of those unique unitids
            $achive_sales_target = Product::whereIn('unitid', $unitids)->sum('price');
            // Imploded unit IDs for passing in URL
            $achive_unit_target_unitids = $unitids->implode(',');
            // Count how many unique unitids there are
            $achive_unit_target = (clone $achive_unit_target_query)->count('product.unitid');

            // ------Lead Graph--------
                $facebook_leads  = Leads::where('source_id', 3 )->whereIn('user_id', $user_ids)->whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])->count();
                $uan             = Leads::where('source_id', 10)->whereIn('user_id', $user_ids)->whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])->count();
                $personal_leads  = Leads::where('source_id', 11)->whereIn('user_id', $user_ids)->whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])->count();
                $dealer_leads    = Leads::where('source_id', 12)->whereIn('user_id', $user_ids)->whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])->count();
                $freelancer_leads= Leads::where('source_id', 13)->whereIn('user_id', $user_ids)->whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])->count();
                $website_leads   = Leads::where('source_id', 14)->whereIn('user_id', $user_ids)->whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])->count();
            // ------Lead Graph---------


            // ======================== Boxes Record =================
                // $achive_number_of_token_target  = Product::where('hold_by', $id)->where('status', 'Token')->whereBetween('sold_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])->count();
                $achive_number_of_token_target = Product::whereIn('hold_by', $user_ids)
                ->where(function($query) {
                    $query->where('status', 'Token')
                        ->orWhere('status', 'Partial Token');
                })
                ->whereBetween('sold_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])->count();

                // // Check Where Condition with Or-Condition
                $achive_contacted_clients_target=Task::whereIn('added_by', $user_ids) // First where clause
                                ->where('type', 'Calls')
                                ->where(function ($query)
                                {
                                    $query->where('subtype', 'Contacted Client')
                                        ->orWhere('subtype', 'Whatsapp Call');
                                })
                                ->whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])
                                ->count();

                // $today_achive_contacted_clients_target=Task::whereIn('added_by', $user_ids) // First where clause
                //                 ->where('type', 'Calls')
                //                 ->where(function ($query)
                //                 {
                //                     $query->where('subtype', 'Contacted Client')
                //                         ->orWhere('subtype', 'Whatsapp Call');
                //                 })
                //                 ->whereDate('created_at', Carbon::today())
                //                 ->count();

                // $today_achive_client_meetings_target= Task::whereIn('added_by', $user_ids)->where('type', 'Meetings')->where('subtype', 'Meeting (Done)')->whereDate('created_at', Carbon::today())->count();

                $achive_new_leads_target = Leads::whereIn('user_id', $user_ids)->whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])->count();

                $achive_affiliator_target=Affiliator::whereIn('user_id', $user_ids)
                            ->where('status', 1)
                            ->whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])
                            ->count();

                $dealer=Affiliator::whereIn('user_id', $user_ids)
                            ->where('status', 1)
                            ->where('type', '=', 'Dealer')
                            ->whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])
                            ->count();

                $freelancer=Affiliator::whereIn('user_id', $user_ids)
                            ->where('status', 1)
                            ->where('type', '=', 'Freelancer')
                            ->whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])
                            ->count();


            // ======================== Boxes Record =================

        }

        if($time_period == 'last_week')
        {
            // Last Week
            $record = Target::whereIn('user_id', $user_ids)->whereMonth('start_date', Carbon::now()->month)->whereYear('start_date', Carbon::now()->format('Y'))->get();
            // $achive_verified_calls_target = Task::where('added_by', $id)->where('type', 'Calls')->where('subtype', 'Contacted Client')->whereBetween('created_at', [Carbon::now()->subWeek()->startOfWeek(), Carbon::now()->subWeek()->endOfWeek()])->count();
            $achive_verified_calls_target=Task::whereIn('added_by', $user_ids) // First where clause
                                            ->where('type', 'Calls')
                                            ->where(function ($query)
                                            {
                                                $query->where('subtype', 'Contacted Client')
                                                    ->orWhere('subtype', 'Whatsapp Call');
                                            })
                                            ->whereBetween('created_at', [Carbon::now()->subWeek()->startOfWeek(), Carbon::now()->subWeek()->endOfWeek()])
                                            ->count();


            $achive_client_meetings_target= Task::whereIn('added_by', $user_ids)->where('type', 'Meetings')->where('subtype', 'Meeting (Done)')->whereBetween('created_at', [Carbon::now()->subWeek()->startOfWeek(), Carbon::now()->subWeek()->endOfWeek()])->count();
            $achive_dealer_meetings_target=AffiliatorTask::join('affiliators', 'affiliators.id', '=', 'affiliatortask.affliator_id')
                                                ->whereIn('affiliatortask.user_id', $user_ids)
                                                ->where('affiliatortask.type', '=', 'Meetings')
                                                ->where('affiliatortask.subtype', '=', 'Meeting (Done)')
                                                ->whereBetween('affiliatortask.created_at', [Carbon::now()->subWeek()->startOfWeek(), Carbon::now()->subWeek()->endOfWeek()])
                                                ->where('affiliators.type', '=', 'Dealer')
                                                // ->get(['affiliatortask.*']);
                                                ->count();

            $achive_freelancer_meetings_target=AffiliatorTask::join('affiliators', 'affiliators.id', '=', 'affiliatortask.affliator_id')
                                                ->whereIn('affiliatortask.user_id', $user_ids)
                                                ->where('affiliatortask.type', '=', 'Meetings')
                                                ->where('affiliatortask.subtype', '=', 'Meeting (Done)')
                                                ->whereBetween('affiliatortask.created_at', [Carbon::now()->subWeek()->startOfWeek(), Carbon::now()->subWeek()->endOfWeek()])
                                                ->where('affiliators.type', '=', 'Freelancer')
                                                ->count();
            $achive_site_visit_target= Task::whereIn('added_by', $user_ids)->where('type', 'Meetings')->where('subtype', 'Meeting (Done)')->where('location', 'Site Office')->whereBetween('created_at', [Carbon::now()->subWeek()->startOfWeek(), Carbon::now()->subWeek()->endOfWeek()])->count();

            $achive_unit_target_query = Product::join('leads', 'leads.item_id', '=', 'product.unitid')
                                    ->where('product.status', '=', 'Sold')
                                    ->whereBetween('product.sold_at', [Carbon::now()->subWeek()->startOfWeek(), Carbon::now()->subWeek()->endOfWeek()])
                                    ->whereIn('leads.user_id', $user_ids)
                                    ->select('product.unitid')
                                    ->distinct();
            // Get the unique unit IDs
            $unitids = (clone $achive_unit_target_query)->pluck('product.unitid');
            // Sum the price of those unique unitids
            $achive_sales_target = Product::whereIn('unitid', $unitids)->sum('price');
            // Imploded unit IDs for passing in URL
            $achive_unit_target_unitids = $unitids->implode(',');
            // Count how many unique unitids there are
            $achive_unit_target = (clone $achive_unit_target_query)->count('product.unitid');

            // ------Lead Graph--------
                $facebook_leads  = Leads::where('source_id', 3 )->whereIn('user_id', $user_ids)->whereBetween('created_at', [Carbon::now()->subWeek()->startOfWeek(), Carbon::now()->subWeek()->endOfWeek()])->count();
                $uan             = Leads::where('source_id', 10)->whereIn('user_id', $user_ids)->whereBetween('created_at', [Carbon::now()->subWeek()->startOfWeek(), Carbon::now()->subWeek()->endOfWeek()])->count();
                $personal_leads  = Leads::where('source_id', 11)->whereIn('user_id', $user_ids)->whereBetween('created_at', [Carbon::now()->subWeek()->startOfWeek(), Carbon::now()->subWeek()->endOfWeek()])->count();
                $dealer_leads    = Leads::where('source_id', 12)->whereIn('user_id', $user_ids)->whereBetween('created_at', [Carbon::now()->subWeek()->startOfWeek(), Carbon::now()->subWeek()->endOfWeek()])->count();
                $freelancer_leads= Leads::where('source_id', 13)->whereIn('user_id', $user_ids)->whereBetween('created_at', [Carbon::now()->subWeek()->startOfWeek(), Carbon::now()->subWeek()->endOfWeek()])->count();
                $website_leads   = Leads::where('source_id', 14)->whereIn('user_id', $user_ids)->whereBetween('created_at', [Carbon::now()->subWeek()->startOfWeek(), Carbon::now()->subWeek()->endOfWeek()])->count();
            // ------Lead Graph---------


            // ======================== Boxes Record =================
                // $achive_number_of_token_target  = Product::where('hold_by', $id)->where('status', 'Token')->whereBetween('sold_at', [Carbon::now()->subWeek()->startOfWeek(), Carbon::now()->subWeek()->endOfWeek()])->count();
                $achive_number_of_token_target = Product::whereIn('hold_by', $user_ids)
                ->where(function($query) {
                    $query->where('status', 'Token')
                        ->orWhere('status', 'Partial Token');
                })
                ->whereBetween('sold_at', [Carbon::now()->subWeek()->startOfWeek(), Carbon::now()->subWeek()->endOfWeek()])->count();


                // // Check Where Condition with Or-Condition
                $achive_contacted_clients_target=Task::whereIn('added_by', $user_ids) // First where clause
                                ->where('type', 'Calls')
                                ->where(function ($query)
                                {
                                    $query->where('subtype', 'Contacted Client')
                                        ->orWhere('subtype', 'Whatsapp Call');
                                })
                                ->whereBetween('created_at', [Carbon::now()->subWeek()->startOfWeek(), Carbon::now()->subWeek()->endOfWeek()])
                                ->count();

                // $today_achive_contacted_clients_target=Task::whereIn('added_by', $user_ids) // First where clause
                //                 ->where('type', 'Calls')
                //                 ->where(function ($query)
                //                 {
                //                     $query->where('subtype', 'Contacted Client')
                //                         ->orWhere('subtype', 'Whatsapp Call');
                //                 })
                //                 ->whereDate('created_at', Carbon::today())
                //                 ->count();

                // $today_achive_client_meetings_target= Task::whereIn('added_by', $user_ids)->where('type', 'Meetings')->where('subtype', 'Meeting (Done)')->whereDate('created_at', Carbon::today())->count();

                $achive_new_leads_target = Leads::whereIn('user_id', $user_ids)->whereBetween('created_at', [Carbon::now()->subWeek()->startOfWeek(), Carbon::now()->subWeek()->endOfWeek()])->count();

                $achive_affiliator_target=Affiliator::whereIn('user_id', $user_ids)
                            ->where('status', 1)
                            ->whereBetween('created_at', [Carbon::now()->subWeek()->startOfWeek(), Carbon::now()->subWeek()->endOfWeek()])
                            ->count();

                $dealer=Affiliator::whereIn('user_id', $user_ids)
                            ->where('status', 1)
                            ->where('type', '=', 'Dealer')
                            ->whereBetween('created_at', [Carbon::now()->subWeek()->startOfWeek(), Carbon::now()->subWeek()->endOfWeek()])
                            ->count();

                $freelancer=Affiliator::whereIn('user_id', $user_ids)
                            ->where('status', 1)
                            ->where('type', '=', 'Freelancer')
                            ->whereBetween('created_at', [Carbon::now()->subWeek()->startOfWeek(), Carbon::now()->subWeek()->endOfWeek()])
                            ->count();
            // ======================== Boxes Record =================

        }

        if ($time_period == 'this_month')
        {
            // current Month
            $record = Target::whereIn('user_id', $user_ids)->whereMonth('start_date', Carbon::now()->month)->whereYear('start_date', Carbon::now()->format('Y'))->get();
            // $achive_verified_calls_target = Task::where('added_by', $id)->where('type', 'Calls')->where('subtype', 'Contacted Client')->whereMonth('created_at', Carbon::now()->month)->whereYear('created_at', Carbon::now()->format('Y'))->count();

            $achive_verified_calls_target=Task::whereIn('added_by', $user_ids) // First where clause
                                        ->where('type', 'Calls')
                                        ->where(function ($query)
                                        {
                                            $query->where('subtype', 'Contacted Client')
                                                ->orWhere('subtype', 'Whatsapp Call');
                                        })
                                        ->whereMonth('created_at', Carbon::now()->month)->whereYear('created_at', Carbon::now()->format('Y'))
                                        ->count();

            $achive_client_meetings_target= Task::whereIn('added_by', $user_ids)->where('type', 'Meetings')->where('subtype', 'Meeting (Done)')->whereMonth('created_at', Carbon::now()->month)->whereYear('created_at', Carbon::now()->format('Y'))->count();
            $achive_dealer_meetings_target=AffiliatorTask::join('affiliators', 'affiliators.id', '=', 'affiliatortask.affliator_id')
                                                ->whereIn('affiliatortask.user_id', $user_ids)
                                                ->where('affiliatortask.type', '=', 'Meetings')
                                                ->where('affiliatortask.subtype', '=', 'Meeting (Done)')
                                                ->whereMonth('affiliatortask.created_at', Carbon::now()->month)
                                                ->whereYear('affiliatortask.created_at', Carbon::now()->format('Y'))
                                                ->where('affiliators.type', '=', 'Dealer')
                                                // ->get(['affiliatortask.*']);
                                                ->count();

            $achive_freelancer_meetings_target=AffiliatorTask::join('affiliators', 'affiliators.id', '=', 'affiliatortask.affliator_id')
                                                ->whereIn('affiliatortask.user_id', $user_ids)
                                                ->where('affiliatortask.type', '=', 'Meetings')
                                                ->where('affiliatortask.subtype', '=', 'Meeting (Done)')
                                                ->whereMonth('affiliatortask.created_at', Carbon::now()->month)
                                                ->whereYear('affiliatortask.created_at', Carbon::now()->format('Y'))
                                                ->where('affiliators.type', '=', 'Freelancer')
                                                ->count();
            $achive_site_visit_target= Task::whereIn('added_by', $user_ids)->where('type', 'Meetings')->where('subtype', 'Meeting (Done)')->where('location', 'Site Office')->whereMonth('created_at', Carbon::now()->month)->whereYear('created_at', Carbon::now()->format('Y'))->count();

            $achive_unit_target_query = Product::join('leads', 'leads.item_id', '=', 'product.unitid')
                                                ->where('product.status', '=', 'Sold')
                                                ->whereMonth('product.sold_at', Carbon::now()->month)
                                                ->whereYear('product.sold_at', Carbon::now()->year)
                                                ->whereIn('leads.user_id', $user_ids)
                                                ->select('product.unitid')
                                                ->distinct();
            // Get the unique unit IDs
            $unitids = (clone $achive_unit_target_query)->pluck('product.unitid');
            // Sum the price of those unique unitids
            $achive_sales_target = Product::whereIn('unitid', $unitids)->sum('price');
            // Imploded unit IDs for passing in URL
            $achive_unit_target_unitids = $unitids->implode(',');
            // Count how many unique unitids there are
            $achive_unit_target = (clone $achive_unit_target_query)->count('product.unitid');

            // ------Lead Graph--------
                $facebook_leads  = Leads::where('source_id', 3 )->whereIn('user_id', $user_ids)->whereMonth('created_at', Carbon::now()->month)->whereYear('created_at', Carbon::now()->format('Y'))->count();
                $uan             = Leads::where('source_id', 10)->whereIn('user_id', $user_ids)->whereMonth('created_at', Carbon::now()->month)->whereYear('created_at', Carbon::now()->format('Y'))->count();
                $personal_leads  = Leads::where('source_id', 11)->whereIn('user_id', $user_ids)->whereMonth('created_at', Carbon::now()->month)->whereYear('created_at', Carbon::now()->format('Y'))->count();
                $dealer_leads    = Leads::where('source_id', 12)->whereIn('user_id', $user_ids)->whereMonth('created_at', Carbon::now()->month)->whereYear('created_at', Carbon::now()->format('Y'))->count();
                $freelancer_leads= Leads::where('source_id', 13)->whereIn('user_id', $user_ids)->whereMonth('created_at', Carbon::now()->month)->whereYear('created_at', Carbon::now()->format('Y'))->count();
                $website_leads   = Leads::where('source_id', 14)->whereIn('user_id', $user_ids)->whereMonth('created_at', Carbon::now()->month)->whereYear('created_at', Carbon::now()->format('Y'))->count();
            // ------Lead Graph---------

            // ======================== Boxes Record =================
                // $achive_number_of_token_target  = Product::where('hold_by', $id)->where('status', 'Token')->whereMonth('sold_at', Carbon::now()->month)->whereYear('sold_at', Carbon::now()->format('Y'))->count();

                $achive_number_of_token_target = Product::whereIn('hold_by', $user_ids)
                ->where(function($query) {
                    $query->where('status', 'Token')
                        ->orWhere('status', 'Partial Token');
                })
                ->whereMonth('sold_at', Carbon::now()->month)->whereYear('sold_at', Carbon::now()->format('Y'))->count();


                // // Check Where Condition with Or-Condition
                $achive_contacted_clients_target=Task::whereIn('added_by', $user_ids) // First where clause
                                ->where('type', 'Calls')
                                ->where(function ($query)
                                {
                                    $query->where('subtype', 'Contacted Client')
                                        ->orWhere('subtype', 'Whatsapp Call');
                                })
                                ->whereMonth('created_at', Carbon::now()->month)->whereYear('created_at', Carbon::now()->format('Y'))
                                ->count();

                // $today_achive_contacted_clients_target=Task::whereIn('added_by', $user_ids) // First where clause
                //                 ->where('type', 'Calls')
                //                 ->where(function ($query)
                //                 {
                //                     $query->where('subtype', 'Contacted Client')
                //                         ->orWhere('subtype', 'Whatsapp Call');
                //                 })
                //                 ->whereDate('created_at', Carbon::today())
                //                 ->count();

                // $today_achive_client_meetings_target= Task::whereIn('added_by', $user_ids)->where('type', 'Meetings')->where('subtype', 'Meeting (Done)')->whereDate('created_at', Carbon::today())->count();

                $achive_new_leads_target = Leads::whereIn('user_id', $user_ids)->whereMonth('created_at', Carbon::now()->month)->whereYear('created_at', Carbon::now()->format('Y'))->count();

                $achive_affiliator_target=Affiliator::whereIn('user_id', $user_ids)
                            ->where('status', 1)
                            ->whereMonth('created_at', Carbon::now()->month)->whereYear('created_at', Carbon::now()->format('Y'))
                            ->count();

                $dealer=Affiliator::whereIn('user_id', $user_ids)
                            ->where('status', 1)
                            ->where('type', '=', 'Dealer')
                            ->whereMonth('created_at', Carbon::now()->month)->whereYear('created_at', Carbon::now()->format('Y'))
                            ->count();

                $freelancer=Affiliator::whereIn('user_id', $user_ids)
                            ->where('status', 1)
                            ->where('type', '=', 'Freelancer')
                            ->whereMonth('created_at', Carbon::now()->month)->whereYear('created_at', Carbon::now()->format('Y'))
                            ->count();


            // ======================== Boxes Record =================

        }

        if($time_period == 'last_month')
        {
            // Previous Month
            // $record = Target::where('user_id', $id)->whereMonth('start_date', Carbon::now()->subMonth(1))->whereYear('start_date', Carbon::now()->format('Y'))->get();
            $record = Target::whereIn('user_id', $user_ids)->whereBetween('start_date', [Carbon::now()->subMonth(1)->startOfMonth(), Carbon::now()->subMonth()->endOfMonth()])->get();
            // $achive_verified_calls_target = Task::where('added_by', $id)->where('type', 'Calls')->where('subtype', 'Contacted Client')->whereBetween('created_at', [Carbon::now()->subMonth(1)->startOfMonth(), Carbon::now()->subMonth()->endOfMonth()])->count();

            $achive_verified_calls_target=Task::whereIn('added_by', $user_ids) // First where clause
                                        ->where('type', 'Calls')
                                        ->where(function ($query)
                                        {
                                            $query->where('subtype', 'Contacted Client')
                                                ->orWhere('subtype', 'Whatsapp Call');
                                        })
                                        ->whereBetween('created_at', [Carbon::now()->subMonth(1)->startOfMonth(), Carbon::now()->subMonth()->endOfMonth()])
                                        ->count();

            $achive_client_meetings_target= Task::whereIn('added_by', $user_ids)->where('type', 'Meetings')->where('subtype', 'Meeting (Done)')->whereBetween('created_at', [Carbon::now()->subMonth(1)->startOfMonth(), Carbon::now()->subMonth()->endOfMonth()])->count();
            $achive_dealer_meetings_target=AffiliatorTask::join('affiliators', 'affiliators.id', '=', 'affiliatortask.affliator_id')
                                                ->whereIn('affiliatortask.user_id', $user_ids)
                                                ->where('affiliatortask.type', '=', 'Meetings')
                                                ->where('affiliatortask.subtype', '=', 'Meeting (Done)')
                                                ->whereBetween('affiliatortask.created_at', [Carbon::now()->subMonth(1)->startOfMonth(), Carbon::now()->subMonth()->endOfMonth()])
                                                ->where('affiliators.type', '=', 'Dealer')
                                                // ->get(['affiliatortask.*']);
                                                ->count();

            $achive_freelancer_meetings_target=AffiliatorTask::join('affiliators', 'affiliators.id', '=', 'affiliatortask.affliator_id')
                                                ->whereIn('affiliatortask.user_id', $user_ids)
                                                ->where('affiliatortask.type', '=', 'Meetings')
                                                ->where('affiliatortask.subtype', '=', 'Meeting (Done)')
                                                ->whereBetween('affiliatortask.created_at', [Carbon::now()->subMonth(1)->startOfMonth(), Carbon::now()->subMonth()->endOfMonth()])
                                                ->where('affiliators.type', '=', 'Freelancer')
                                                ->count();
            $achive_site_visit_target= Task::whereIn('added_by', $user_ids)->where('type', 'Meetings')->where('subtype', 'Meeting (Done)')->where('location', 'Site Office')->whereBetween('created_at', [Carbon::now()->subMonth(1)->startOfMonth(), Carbon::now()->subMonth()->endOfMonth()])->count();


            $achive_unit_target_query = Product::join('leads', 'leads.item_id', '=', 'product.unitid')
                                                ->where('product.status', '=', 'Sold')
                                                ->whereBetween('product.sold_at', [Carbon::now()->subMonth()->startOfMonth(),Carbon::now()->subMonth()->endOfMonth()])
                                                ->whereIn('leads.user_id', $user_ids)
                                                ->select('product.unitid')
                                                ->distinct();

            // Get the unique unit IDs
            $unitids = (clone $achive_unit_target_query)->pluck('product.unitid');
            // Sum the price of those unique unitids
            $achive_sales_target = Product::whereIn('unitid', $unitids)->sum('price');
            // Imploded unit IDs for passing in URL
            $achive_unit_target_unitids = $unitids->implode(',');
            // Count how many unique unitids there are
            $achive_unit_target = (clone $achive_unit_target_query)->count('product.unitid');


            // ------Lead Graph--------
                $facebook_leads  = Leads::where('source_id', 3 )->whereIn('user_id', $user_ids )->whereBetween('created_at', [Carbon::now()->subMonth(1)->startOfMonth(), Carbon::now()->subMonth()->endOfMonth()])->count();
                $uan             = Leads::where('source_id', 10)->whereIn('user_id', $user_ids )->whereBetween('created_at', [Carbon::now()->subMonth(1)->startOfMonth(), Carbon::now()->subMonth()->endOfMonth()])->count();
                $personal_leads  = Leads::where('source_id', 11)->whereIn('user_id', $user_ids )->whereBetween('created_at', [Carbon::now()->subMonth(1)->startOfMonth(), Carbon::now()->subMonth()->endOfMonth()])->count();
                $dealer_leads    = Leads::where('source_id', 12)->whereIn('user_id', $user_ids )->whereBetween('created_at', [Carbon::now()->subMonth(1)->startOfMonth(), Carbon::now()->subMonth()->endOfMonth()])->count();
                $freelancer_leads= Leads::where('source_id', 13)->whereIn('user_id', $user_ids )->whereBetween('created_at', [Carbon::now()->subMonth(1)->startOfMonth(), Carbon::now()->subMonth()->endOfMonth()])->count();
                $website_leads   = Leads::where('source_id', 14)->whereIn('user_id', $user_ids )->whereBetween('created_at', [Carbon::now()->subMonth(1)->startOfMonth(), Carbon::now()->subMonth()->endOfMonth()])->count();
           // ------Lead Graph---------

            // ======================== Boxes Record =================
                // $achive_number_of_token_target  = Product::where('hold_by', $id)->where('status', 'Token')->whereBetween('sold_at', [Carbon::now()->subMonth(1)->startOfMonth(), Carbon::now()->subMonth()->endOfMonth()])->count();
                $achive_number_of_token_target = Product::whereIn('hold_by', $user_ids)
                ->where(function($query) {
                    $query->where('status', 'Token')
                        ->orWhere('status', 'Partial Token');
                })
                ->whereBetween('sold_at', [Carbon::now()->subMonth(1)->startOfMonth(), Carbon::now()->subMonth()->endOfMonth()])->count();


                // // Check Where Condition with Or-Condition
                $achive_contacted_clients_target=Task::whereIn('added_by', $user_ids) // First where clause
                                ->where('type', 'Calls')
                                ->where(function ($query)
                                {
                                    $query->where('subtype', 'Contacted Client')
                                        ->orWhere('subtype', 'Whatsapp Call');
                                })
                                ->whereBetween('created_at', [Carbon::now()->subMonth(1)->startOfMonth(), Carbon::now()->subMonth()->endOfMonth()])
                                ->count();

                // $today_achive_contacted_clients_target=Task::whereIn('added_by', $user_ids) // First where clause
                //                 ->where('type', 'Calls')
                //                 ->where(function ($query)
                //                 {
                //                     $query->where('subtype', 'Contacted Client')
                //                         ->orWhere('subtype', 'Whatsapp Call');
                //                 })
                //                 ->whereDate('created_at', Carbon::today())
                //                 ->count();

                // $today_achive_client_meetings_target= Task::whereIn('added_by', $user_ids)->where('type', 'Meetings')->where('subtype', 'Meeting (Done)')->whereDate('created_at', Carbon::today())->count();

                $achive_new_leads_target = Leads::whereIn('user_id', $user_ids)->whereBetween('created_at', [Carbon::now()->subMonth(1)->startOfMonth(), Carbon::now()->subMonth()->endOfMonth()])->count();

                $achive_affiliator_target=Affiliator::whereIn('user_id', $user_ids)
                            ->where('status', 1)
                            ->whereBetween('created_at', [Carbon::now()->subMonth(1)->startOfMonth(), Carbon::now()->subMonth()->endOfMonth()])
                            ->count();

                $dealer=Affiliator::whereIn('user_id', $user_ids)
                            ->where('status', 1)
                            ->where('type', '=', 'Dealer')
                            ->whereBetween('created_at', [Carbon::now()->subMonth(1)->startOfMonth(), Carbon::now()->subMonth()->endOfMonth()])
                            ->count();

                $freelancer=Affiliator::whereIn('user_id', $user_ids)
                            ->where('status', 1)
                            ->where('type', '=', 'Freelancer')
                            ->whereBetween('created_at', [Carbon::now()->subMonth(1)->startOfMonth(), Carbon::now()->subMonth()->endOfMonth()])
                            ->count();
            // ======================== Boxes Record =================
        }

        if($time_period == 'three_month')
        {
            // Previous 3 Months

            $record = Target::whereIn('user_id', $user_ids)->whereBetween('start_date', [Carbon::now()->subMonth(3)->startOfMonth(), Carbon::now()->subMonth()->endOfMonth()])->get();
            // $achive_verified_calls_target = Task::where('added_by', $id)->where('type', 'Calls')->where('subtype', 'Contacted Client')->whereBetween('created_at', [Carbon::now()->subMonth(3)->startOfMonth(), Carbon::now()->subMonth()->endOfMonth()])->count();

            $achive_verified_calls_target=Task::whereIn('added_by', $user_ids) // First where clause
                                        ->where('type', 'Calls')
                                        ->where(function ($query)
                                        {
                                            $query->where('subtype', 'Contacted Client')
                                                ->orWhere('subtype', 'Whatsapp Call');
                                        })
                                        ->whereBetween('created_at', [Carbon::now()->subMonth(3)->startOfMonth(), Carbon::now()->subMonth()->endOfMonth()])
                                        ->count();

            $achive_client_meetings_target= Task::whereIn('added_by', $user_ids)->where('type', 'Meetings')->where('subtype', 'Meeting (Done)')->whereBetween('created_at', [Carbon::now()->subMonth(3)->startOfMonth(), Carbon::now()->subMonth()->endOfMonth()])->count();
            $achive_dealer_meetings_target=AffiliatorTask::join('affiliators', 'affiliators.id', '=', 'affiliatortask.affliator_id')
                                                ->whereIn('affiliatortask.user_id', $user_ids)
                                                ->where('affiliatortask.type', '=', 'Meetings')
                                                ->where('affiliatortask.subtype', '=', 'Meeting (Done)')
                                                ->whereBetween('affiliatortask.created_at', [Carbon::now()->subMonth(3)->startOfMonth(), Carbon::now()->subMonth()->endOfMonth()])
                                                ->where('affiliators.type', '=', 'Dealer')
                                                // ->get(['affiliatortask.*']);
                                                ->count();

            $achive_freelancer_meetings_target=AffiliatorTask::join('affiliators', 'affiliators.id', '=', 'affiliatortask.affliator_id')
                                                ->whereIn('affiliatortask.user_id', $user_ids)
                                                ->where('affiliatortask.type', '=', 'Meetings')
                                                ->where('affiliatortask.subtype', '=', 'Meeting (Done)')
                                                ->whereBetween('affiliatortask.created_at', [Carbon::now()->subMonth(3)->startOfMonth(), Carbon::now()->subMonth()->endOfMonth()])
                                                ->where('affiliators.type', '=', 'Freelancer')
                                                ->count();
            $achive_site_visit_target= Task::whereIn('added_by', $user_ids)->where('type', 'Meetings')->where('subtype', 'Meeting (Done)')->where('location', 'Site Office')->whereBetween('created_at', [Carbon::now()->subMonth(3)->startOfMonth(), Carbon::now()->subMonth()->endOfMonth()])->count();

            $achive_unit_target_query = Product::join('leads', 'leads.item_id', '=', 'product.unitid')
                                                ->where('product.status', '=', 'Sold')
                                                ->whereBetween('product.sold_at', [Carbon::now()->subMonth(3)->startOfMonth(), Carbon::now()->subMonth()->endOfMonth()])
                                                ->whereIn('leads.user_id', $user_ids)
                                                ->select('product.unitid')
                                                ->distinct();
            // Get the unique unit IDs
            $unitids = (clone $achive_unit_target_query)->pluck('product.unitid');
            // Sum the price of those unique unitids
            $achive_sales_target = Product::whereIn('unitid', $unitids)->sum('price');
            // Imploded unit IDs for passing in URL
            $achive_unit_target_unitids = $unitids->implode(',');
            // Count how many unique unitids there are
            $achive_unit_target = (clone $achive_unit_target_query)->count('product.unitid');

            // ------Lead Graph--------
                $facebook_leads  = Leads::where('source_id', 3 )->whereIn('user_id', $user_ids)->whereBetween('created_at', [Carbon::now()->subMonth(3)->startOfMonth(), Carbon::now()->subMonth()->endOfMonth()])->count();
                $uan             = Leads::where('source_id', 10)->whereIn('user_id', $user_ids)->whereBetween('created_at', [Carbon::now()->subMonth(3)->startOfMonth(), Carbon::now()->subMonth()->endOfMonth()])->count();
                $personal_leads  = Leads::where('source_id', 11)->whereIn('user_id', $user_ids)->whereBetween('created_at', [Carbon::now()->subMonth(3)->startOfMonth(), Carbon::now()->subMonth()->endOfMonth()])->count();
                $dealer_leads    = Leads::where('source_id', 12)->whereIn('user_id', $user_ids)->whereBetween('created_at', [Carbon::now()->subMonth(3)->startOfMonth(), Carbon::now()->subMonth()->endOfMonth()])->count();
                $freelancer_leads= Leads::where('source_id', 13)->whereIn('user_id', $user_ids)->whereBetween('created_at', [Carbon::now()->subMonth(3)->startOfMonth(), Carbon::now()->subMonth()->endOfMonth()])->count();
                $website_leads   = Leads::where('source_id', 14)->whereIn('user_id', $user_ids)->whereBetween('created_at', [Carbon::now()->subMonth(3)->startOfMonth(), Carbon::now()->subMonth()->endOfMonth()])->count();
            // ------Lead Graph---------

            // ======================== Boxes Record =================
                // $achive_number_of_token_target  = Product::where('hold_by', $id)->where('status', 'Token')->whereBetween('sold_at', [Carbon::now()->subMonth(3)->startOfMonth(), Carbon::now()->subMonth()->endOfMonth()])->count();
                $achive_number_of_token_target = Product::whereIn('hold_by', $user_ids)
                ->where(function($query) {
                    $query->where('status', 'Token')
                        ->orWhere('status', 'Partial Token');
                })
                ->whereBetween('sold_at', [Carbon::now()->subMonth(3)->startOfMonth(), Carbon::now()->subMonth()->endOfMonth()])->count();


                // // Check Where Condition with Or-Condition
                $achive_contacted_clients_target=Task::whereIn('added_by', $user_ids) // First where clause
                                ->where('type', 'Calls')
                                ->where(function ($query)
                                {
                                    $query->where('subtype', 'Contacted Client')
                                        ->orWhere('subtype', 'Whatsapp Call');
                                })
                                ->whereBetween('created_at', [Carbon::now()->subMonth(3)->startOfMonth(), Carbon::now()->subMonth()->endOfMonth()])
                                ->count();

                // $today_achive_contacted_clients_target=Task::whereIn('added_by', $user_ids) // First where clause
                //                 ->where('type', 'Calls')
                //                 ->where(function ($query)
                //                 {
                //                     $query->where('subtype', 'Contacted Client')
                //                         ->orWhere('subtype', 'Whatsapp Call');
                //                 })
                //                 ->whereDate('created_at', Carbon::today())
                //                 ->count();

                // $today_achive_client_meetings_target= Task::whereIn('added_by', $user_ids)->where('type', 'Meetings')->where('subtype', 'Meeting (Done)')->whereDate('created_at', Carbon::today())->count();

                $achive_new_leads_target = Leads::whereIn('user_id', $user_ids)->whereBetween('created_at', [Carbon::now()->subMonth(3)->startOfMonth(), Carbon::now()->subMonth()->endOfMonth()])->count();

                $achive_affiliator_target=Affiliator::whereIn('user_id', $user_ids)
                            ->where('status', 1)
                            ->whereBetween('created_at', [Carbon::now()->subMonth(3)->startOfMonth(), Carbon::now()->subMonth()->endOfMonth()])
                            ->count();

                $dealer=Affiliator::whereIn('user_id', $user_ids)
                            ->where('status', 1)
                            ->where('type', '=', 'Dealer')
                            ->whereBetween('created_at', [Carbon::now()->subMonth(3)->startOfMonth(), Carbon::now()->subMonth()->endOfMonth()])
                            ->count();

                $freelancer=Affiliator::whereIn('user_id', $user_ids)
                            ->where('status', 1)
                            ->where('type', '=', 'Freelancer')
                            ->whereBetween('created_at', [Carbon::now()->subMonth(3)->startOfMonth(), Carbon::now()->subMonth()->endOfMonth()])
                            ->count();
            // ======================== Boxes Record =================

        }

        if($time_period == 'six_month')
        {
            // Previous 6 Months
            $record = Target::whereIn('user_id', $user_ids)->whereBetween('start_date', [Carbon::now()->subMonth(6)->startOfMonth(), Carbon::now()->subMonth()->endOfMonth()])->get();
            // $achive_verified_calls_target   = Task::where('added_by', $id)->where('type', 'Calls')->where('subtype', 'Contacted Client')->whereBetween('created_at', [Carbon::now()->subMonth(6)->startOfMonth(), Carbon::now()->subMonth()->endOfMonth()])->count();

            $achive_verified_calls_target=Task::whereIn('added_by', $user_ids) // First where clause
                                                ->where('type', 'Calls')
                                                ->where(function ($query)
                                                {
                                                    $query->where('subtype', 'Contacted Client')
                                                        ->orWhere('subtype', 'Whatsapp Call');
                                                })
                                                ->whereBetween('created_at', [Carbon::now()->subMonth(6)->startOfMonth(), Carbon::now()->subMonth()->endOfMonth()])
                                                ->count();

            $achive_client_meetings_target  = Task::whereIn('added_by', $user_ids)->where('type', 'Meetings')->where('subtype', 'Meeting (Done)')->whereBetween('created_at', [Carbon::now()->subMonth(6)->startOfMonth(), Carbon::now()->subMonth()->endOfMonth()])->count();
            $achive_dealer_meetings_target  =AffiliatorTask::join('affiliators', 'affiliators.id', '=', 'affiliatortask.affliator_id')
                                                ->whereIn('affiliatortask.user_id', $user_ids)
                                                ->where('affiliatortask.type', '=', 'Meetings')
                                                ->where('affiliatortask.subtype', '=', 'Meeting (Done)')
                                                ->whereBetween('affiliatortask.created_at', [Carbon::now()->subMonth(6)->startOfMonth(), Carbon::now()->subMonth()->endOfMonth()])
                                                ->where('affiliators.type', '=', 'Dealer')
                                                // ->get(['affiliatortask.*']);
                                                ->count();

            $achive_freelancer_meetings_target=AffiliatorTask::join('affiliators', 'affiliators.id', '=', 'affiliatortask.affliator_id')
                                                ->whereIn('affiliatortask.user_id', $user_ids)
                                                ->where('affiliatortask.type', '=', 'Meetings')
                                                ->where('affiliatortask.subtype', '=', 'Meeting (Done)')
                                                ->whereBetween('affiliatortask.created_at', [Carbon::now()->subMonth(6)->startOfMonth(), Carbon::now()->subMonth()->endOfMonth()])
                                                ->where('affiliators.type', '=', 'Freelancer')
                                                ->count();
            $achive_site_visit_target       = Task::whereIn('added_by', $user_ids)->where('type', 'Meetings')->where('subtype', 'Meeting (Done)')->where('location', 'Site Office')->whereBetween('created_at', [Carbon::now()->subMonth(6)->startOfMonth(), Carbon::now()->subMonth()->endOfMonth()])->count();

            $achive_unit_target_query = Product::join('leads', 'leads.item_id', '=', 'product.unitid')
                                                ->where('product.status', '=', 'Sold')
                                                ->whereBetween('product.sold_at', [Carbon::now()->subMonth(6)->startOfMonth(), Carbon::now()->subMonth()->endOfMonth()])
                                                ->whereIn('leads.user_id', $user_ids)
                                                ->select('product.unitid')
                                                ->distinct();
            // Get the unique unit IDs
            $unitids = (clone $achive_unit_target_query)->pluck('product.unitid');
            // Sum the price of those unique unitids
            $achive_sales_target = Product::whereIn('unitid', $unitids)->sum('price');
            // Imploded unit IDs for passing in URL
            $achive_unit_target_unitids = $unitids->implode(',');
            // Count how many unique unitids there are
            $achive_unit_target = (clone $achive_unit_target_query)->count('product.unitid');

            // ------Lead Graph--------
                $facebook_leads  = Leads::where('source_id', 3 )->whereIn('user_id', $user_ids)->whereBetween('created_at', [Carbon::now()->subMonth(6)->startOfMonth(), Carbon::now()->subMonth()->endOfMonth()])->count();
                $uan             = Leads::where('source_id', 10)->whereIn('user_id', $user_ids)->whereBetween('created_at', [Carbon::now()->subMonth(6)->startOfMonth(), Carbon::now()->subMonth()->endOfMonth()])->count();
                $personal_leads  = Leads::where('source_id', 11)->whereIn('user_id', $user_ids)->whereBetween('created_at', [Carbon::now()->subMonth(6)->startOfMonth(), Carbon::now()->subMonth()->endOfMonth()])->count();
                $dealer_leads    = Leads::where('source_id', 12)->whereIn('user_id', $user_ids)->whereBetween('created_at', [Carbon::now()->subMonth(6)->startOfMonth(), Carbon::now()->subMonth()->endOfMonth()])->count();
                $freelancer_leads= Leads::where('source_id', 13)->whereIn('user_id', $user_ids)->whereBetween('created_at', [Carbon::now()->subMonth(6)->startOfMonth(), Carbon::now()->subMonth()->endOfMonth()])->count();
                $website_leads   = Leads::where('source_id', 14)->whereIn('user_id', $user_ids)->whereBetween('created_at', [Carbon::now()->subMonth(6)->startOfMonth(), Carbon::now()->subMonth()->endOfMonth()])->count();
            // ------Lead Graph---------

            // ======================== Boxes Record =================
                // $achive_number_of_token_target  = Product::where('hold_by', $id)->where('status', 'Token')->whereBetween('sold_at', [Carbon::now()->subMonth(6)->startOfMonth(), Carbon::now()->subMonth()->endOfMonth()])->count();

                $achive_number_of_token_target = Product::whereIn('hold_by', $user_ids)
                ->where(function($query) {
                    $query->where('status', 'Token')
                        ->orWhere('status', 'Partial Token');
                })
                ->whereBetween('sold_at', [Carbon::now()->subMonth(6)->startOfMonth(), Carbon::now()->subMonth()->endOfMonth()])->count();

                // // Check Where Condition with Or-Condition
                $achive_contacted_clients_target=Task::whereIn('added_by', $user_ids) // First where clause
                                ->where('type', 'Calls')
                                ->where(function ($query)
                                {
                                    $query->where('subtype', 'Contacted Client')
                                        ->orWhere('subtype', 'Whatsapp Call');
                                })
                                ->whereBetween('created_at', [Carbon::now()->subMonth(6)->startOfMonth(), Carbon::now()->subMonth()->endOfMonth()])
                                ->count();

                // $today_achive_contacted_clients_target=Task::whereIn('added_by', $user_ids) // First where clause
                //                 ->where('type', 'Calls')
                //                 ->where(function ($query)
                //                 {
                //                     $query->where('subtype', 'Contacted Client')
                //                         ->orWhere('subtype', 'Whatsapp Call');
                //                 })
                //                 ->whereDate('created_at', Carbon::today())
                //                 ->count();


                // $today_achive_client_meetings_target= Task::whereIn('added_by', $user_ids)->where('type', 'Meetings')->where('subtype', 'Meeting (Done)')->whereDate('created_at', Carbon::today())->count();

                $achive_new_leads_target = Leads::whereIn('user_id', $user_ids)->whereBetween('created_at', [Carbon::now()->subMonth(6)->startOfMonth(), Carbon::now()->subMonth()->endOfMonth()])->count();

                $achive_affiliator_target=Affiliator::whereIn('user_id', $user_ids)
                            ->where('status', 1)
                            ->whereBetween('created_at', [Carbon::now()->subMonth(6)->startOfMonth(), Carbon::now()->subMonth()->endOfMonth()])
                            ->count();

                $dealer=Affiliator::whereIn('user_id', $user_ids)
                            ->where('status', 1)
                            ->where('type', '=', 'Dealer')
                            ->whereBetween('created_at', [Carbon::now()->subMonth(6)->startOfMonth(), Carbon::now()->subMonth()->endOfMonth()])
                            ->count();

                $freelancer=Affiliator::whereIn('user_id', $user_ids)
                            ->where('status', 1)
                            ->where('type', '=', 'Freelancer')
                            ->whereBetween('created_at', [Carbon::now()->subMonth(6)->startOfMonth(), Carbon::now()->subMonth()->endOfMonth()])
                            ->count();
            // ======================== Boxes Record =================

        }

        if($time_period == 'this_year')
        {
            // This Year
            $record = Target::whereIn('user_id', $user_ids)->whereYear('start_date', Carbon::now()->format('Y'))->get();
            // $achive_verified_calls_target = Task::where('added_by', $id)->where('type', 'Calls')->where('subtype', 'Contacted Client')->whereYear('created_at', Carbon::now()->format('Y'))->count();

            $achive_verified_calls_target=Task::whereIn('added_by', $user_ids) // First where clause
                                        ->where('type', 'Calls')
                                        ->where(function ($query)
                                        {
                                            $query->where('subtype', 'Contacted Client')
                                                ->orWhere('subtype', 'Whatsapp Call');
                                        })
                                        ->whereYear('created_at', Carbon::now()->format('Y'))
                                        ->count();

            $achive_client_meetings_target= Task::whereIn('added_by', $user_ids)->where('type', 'Meetings')->where('subtype', 'Meeting (Done)')->whereYear('created_at', Carbon::now()->format('Y'))->count();
            $achive_dealer_meetings_target=AffiliatorTask::join('affiliators', 'affiliators.id', '=', 'affiliatortask.affliator_id')
                                                ->whereIn('affiliatortask.user_id', $user_ids)
                                                ->where('affiliatortask.type', '=', 'Meetings')
                                                ->where('affiliatortask.subtype', '=', 'Meeting (Done)')
                                                ->whereYear('affiliatortask.created_at', '=', Carbon::now()->format('Y'))
                                                ->where('affiliators.type', '=', 'Dealer')
                                                // ->get(['affiliatortask.*']);
                                                ->count();

            $achive_freelancer_meetings_target=AffiliatorTask::join('affiliators', 'affiliators.id', '=', 'affiliatortask.affliator_id')
                                                ->whereIn('affiliatortask.user_id', $user_ids)
                                                ->where('affiliatortask.type', '=', 'Meetings')
                                                ->where('affiliatortask.subtype', '=', 'Meeting (Done)')
                                                ->whereYear('affiliatortask.created_at', '=', Carbon::now()->format('Y'))
                                                ->where('affiliators.type', '=', 'Freelancer')
                                                ->count();
            $achive_site_visit_target= Task::whereIn('added_by', $user_ids)->where('type', 'Meetings')->where('subtype', 'Meeting (Done)')->where('location', 'Site Office')->whereYear('created_at', Carbon::now()->format('Y'))->count();

            $achive_unit_target_query = Product::join('leads', 'leads.item_id', '=', 'product.unitid')
                                    ->where('product.status', '=', 'Sold')
                                    ->whereYear('product.sold_at', Carbon::now()->year)
                                    ->whereIn('leads.user_id', $user_ids)
                                    ->select('product.unitid')
                                    ->distinct();
            // Get the unique unit IDs
            $unitids = (clone $achive_unit_target_query)->pluck('product.unitid');
            // Sum the price of those unique unitids
            $achive_sales_target = Product::whereIn('unitid', $unitids)->sum('price');
            // Imploded unit IDs for passing in URL
            $achive_unit_target_unitids = $unitids->implode(',');
            // Count how many unique unitids there are
            $achive_unit_target = (clone $achive_unit_target_query)->count('product.unitid');

            // ------Lead Graph--------
                $facebook_leads  = Leads::where('source_id', 3 )->whereIn('user_id', $user_ids)->whereYear('created_at', Carbon::now()->format('Y'))->count();
                $uan             = Leads::where('source_id', 10)->whereIn('user_id', $user_ids)->whereYear('created_at', Carbon::now()->format('Y'))->count();
                $personal_leads  = Leads::where('source_id', 11)->whereIn('user_id', $user_ids)->whereYear('created_at', Carbon::now()->format('Y'))->count();
                $dealer_leads    = Leads::where('source_id', 12)->whereIn('user_id', $user_ids)->whereYear('created_at', Carbon::now()->format('Y'))->count();
                $freelancer_leads= Leads::where('source_id', 13)->whereIn('user_id', $user_ids)->whereYear('created_at', Carbon::now()->format('Y'))->count();
                $website_leads   = Leads::where('source_id', 14)->whereIn('user_id', $user_ids)->whereYear('created_at', Carbon::now()->format('Y'))->count();
            // ------Lead Graph---------

            // ======================== Boxes Record =================
                // $achive_number_of_token_target  = Product::where('hold_by', $id)->where('status', 'Token')->whereYear('sold_at', Carbon::now()->format('Y'))->count();

                $achive_number_of_token_target = Product::whereIn('hold_by', $user_ids)
                ->where(function($query) {
                    $query->where('status', 'Token')
                        ->orWhere('status', 'Partial Token');
                })
                ->whereYear('sold_at', Carbon::now()->format('Y'))->count();


                // // Check Where Condition with Or-Condition
                $achive_contacted_clients_target=Task::whereIn('added_by', $user_ids) // First where clause
                                ->where('type', 'Calls')
                                ->where(function ($query)
                                {
                                    $query->where('subtype', 'Contacted Client')
                                        ->orWhere('subtype', 'Whatsapp Call');
                                })
                                ->whereYear('created_at', Carbon::now()->format('Y'))
                                ->count();

                // $today_achive_contacted_clients_target=Task::whereIn('added_by', $user_ids) // First where clause
                //                 ->where('type', 'Calls')
                //                 ->where(function ($query)
                //                 {
                //                     $query->where('subtype', 'Contacted Client')
                //                         ->orWhere('subtype', 'Whatsapp Call');
                //                 })
                //                 ->whereDate('created_at', Carbon::today())
                //                 ->count();

                // $today_achive_client_meetings_target= Task::whereIn('added_by', $user_ids)->where('type', 'Meetings')->where('subtype', 'Meeting (Done)')->whereDate('created_at', Carbon::today())->count();


                $achive_new_leads_target = Leads::whereIn('user_id', $user_ids)->whereYear('created_at', Carbon::now()->format('Y'))->count();

                $achive_affiliator_target=Affiliator::whereIn('user_id', $user_ids)
                            ->where('status', 1)
                            ->whereYear('created_at', Carbon::now()->format('Y'))
                            ->count();

                $dealer=Affiliator::whereIn('user_id', $user_ids)
                            ->where('status', 1)
                            ->where('type', '=', 'Dealer')
                            ->whereYear('created_at', Carbon::now()->format('Y'))
                            ->count();

                $freelancer=Affiliator::whereIn('user_id', $user_ids)
                            ->where('status', 1)
                            ->where('type', '=', 'Freelancer')
                            ->whereYear('created_at', Carbon::now()->format('Y'))
                            ->count();
            // ======================== Boxes Record =================

        }

        // echo '<pre>'; print_r(count($record));exit;

        if(count($record) > 0)
        {
            if($request->time_period == 'custom_date' && ($request->startDate != '' || $request->endDate != ''))
            {
                // echo '<pre>'; print_r($days_btwn_dates);exit;

                // $set_sales_target               = $for_sale_and_unit->sum('set_sales_target');
                // $set_unit_target                = $for_sale_and_unit->sum('set_unit_target');

                $set_sales_target                = ( $record->sum('set_sales_target')                / $total_days) * $days_btwn_dates;
                $set_unit_target                 = ( $record->sum('set_unit_target')                 / $total_days) * $days_btwn_dates;
                $set_verified_calls_target       = ( $record->sum('set_verified_calls_target')       / $total_days) * $days_btwn_dates;
                $set_client_meetings_target      = ( $record->sum('set_client_meetings_target')      / $total_days) * $days_btwn_dates;
                $set_dealer_meetings_target      = ( $record->sum('set_dealer_meetings_target')      / $total_days) * $days_btwn_dates;
                $set_freelancer_meetings_target  = ( $record->sum('set_freelancer_meetings_target')  / $total_days) * $days_btwn_dates;
                $set_site_visit_target           = ( $record->sum('set_site_visit_target')           / $total_days) * $days_btwn_dates;

                $set_leads_target                = ($record->sum('set_lead_target')                  / $total_days) * $days_btwn_dates;
                $set_affiliator_target           = ($record->sum('set_affiliator_target')            / $total_days) * $days_btwn_dates;

                $today_set_verified_calls_target = ($record->sum('perday_verified_calls_target')     / $total_days) * $days_btwn_dates;
                $today_set_client_meetings_target= ($record->sum('perday_client_meetings_target')    / $total_days) * $days_btwn_dates;

            }
            elseif($request->time_period == 'today')
            {
                // dd('today');

                // $set_sales_target               = $for_sale_and_unit->sum('set_sales_target');
                // $set_unit_target                = $for_sale_and_unit->sum('set_unit_target');

                $set_sales_target                   = $record->sum('perday_sales_target');
                $set_unit_target                    = $record->sum('perday_unit_target');
                $set_verified_calls_target          = $record->sum('perday_verified_calls_target');
                $set_client_meetings_target         = $record->sum('perday_client_meetings_target');
                $set_dealer_meetings_target         = $record->sum('perday_dealer_meetings_target');
                $set_freelancer_meetings_target     = $record->sum('perday_freelancer_meetings_target');
                $set_site_visit_target              = $record->sum('perday_site_visit_target') ;

                $set_leads_target                   = $record->sum('perday_lead_target');
                $set_affiliator_target              = $record->sum('perday_affiliator_target');
                $today_set_verified_calls_target    = $record->sum('perday_verified_calls_target');
                $today_set_client_meetings_target   = $record->sum('perday_client_meetings_target');

            }
            elseif($request->time_period == 'this_week' || $request->time_period == 'last_week')
            {
                if($request->time_period == 'this_week')
                {
                    $week_start= Carbon::now()->startOfWeek();
                    $week_end  = Carbon::now()->endOfWeek();
                }
                if($request->time_period == 'last_week')
                {
                    $week_start= Carbon::now()->subWeek()->startOfWeek();
                    $week_end  = Carbon::now()->subWeek()->endOfWeek();
                }

                $month_start= date('m',strtotime($week_start));
                $month_end  = date('m',strtotime($week_end));

                $days_count = 0;

                if($month_start == $month_end)
                {
                    $period = CarbonPeriod::create($week_start, $week_end);

                    foreach ($period as $date)
                    {
                        if ($date->dayOfWeek !== Carbon::SUNDAY)
                        {
                            $days_count++;
                        }
                    }
                }
                // else{return redirect('/')->withErrors(__('Weeks Are Month Not Matched Please Use Custom Date.!'));}
                else{

                    $lastWeekStart = Carbon::now()->subMonth()->endOfMonth()->startOfWeek();
                    $lastWeekEnd = Carbon::now()->subMonth()->endOfMonth();

                    $period = CarbonPeriod::create($lastWeekStart, $lastWeekEnd);

                    foreach ($period as $date)
                    {
                        if ($date->dayOfWeek !== Carbon::SUNDAY)
                        {
                            $days_count++;
                        }
                    }
                }

                // $set_sales_target                = $record->sum('set_sales_target');
                // $set_unit_target                 = $record->sum('set_unit_target');

                $set_sales_target                = $record->sum('perday_sales_target')               * $days_count;
                $set_unit_target                 = $record->sum('perday_unit_target')                * $days_count;
                $set_verified_calls_target       = $record->sum('perday_verified_calls_target')      * $days_count;
                $set_client_meetings_target      = $record->sum('perday_client_meetings_target')     * $days_count;
                $set_dealer_meetings_target      = $record->sum('perday_dealer_meetings_target')     * $days_count;
                $set_freelancer_meetings_target  = $record->sum('perday_freelancer_meetings_target') * $days_count;
                $set_site_visit_target           = $record->sum('perday_site_visit_target')          * $days_count;

                $set_leads_target                = $record->sum('perday_lead_target')                * $days_count;
                $set_affiliator_target           = $record->sum('perday_affiliator_target')          * $days_count;
                $today_set_verified_calls_target = $record->sum('perday_verified_calls_target') ;
                $today_set_client_meetings_target= $record->sum('perday_client_meetings_target') ;

            }
            else
            {
                // echo '<pre>'; print_r(count($record));exit;

                // $set_sales_target               = $for_sale_and_unit->sum('set_sales_target');
                // $set_unit_target                = $for_sale_and_unit->sum('set_unit_target');

                $set_sales_target               = $record->sum('set_sales_target');
                $set_unit_target                = $record->sum('set_unit_target');

                $set_verified_calls_target      = $record->sum('set_verified_calls_target');
                $set_client_meetings_target     = $record->sum('set_client_meetings_target');
                $set_dealer_meetings_target     = $record->sum('set_dealer_meetings_target');
                $set_freelancer_meetings_target = $record->sum('set_freelancer_meetings_target');
                $set_site_visit_target          = $record->sum('set_site_visit_target');

                $set_leads_target                = $record->sum('set_lead_target') ;
                $set_affiliator_target           = $record->sum('set_affiliator_target') ;
                $today_set_verified_calls_target = $record->sum('perday_verified_calls_target') ;
                $today_set_client_meetings_target= $record->sum('perday_client_meetings_target') ;


            }
        }



        // // ========================Overall Record =================


        //    $overall_record = Target::where('user_id', $id)->get();

        //    $overall_set_sales_target               =  $overall_record->sum('set_sales_target');
        //    $overall_set_unit_target                =  $overall_record->sum('set_unit_target');
        //    $overall_set_lead_target                =  $overall_record->sum('set_lead_target');
        //    $overall_set_affiliator_target          =  $overall_record->sum('set_affiliator_target');
        //    $overall_set_verified_calls_target      =  $overall_record->sum('set_verified_calls_target');
        //    $overall_set_client_meetings_target     =  $overall_record->sum('set_client_meetings_target');
        //    $overall_set_dealer_meetings_target     =  $overall_record->sum('set_dealer_meetings_target');
        //    $overall_set_freelancer_meetings_target =  $overall_record->sum('set_freelancer_meetings_target');
        //    $overall_set_site_visit_target          =  $overall_record->sum('set_site_visit_target');

        //    //  overall Achive
        //    $overall_achive_sales_target = Product::join('leads', 'leads.item_id', '=', 'product.unitid')
        //                                    ->where('product.status', '=', 'Sold')
        //                                    ->where('leads.user_id', '=', $id)
        //                                    ->sum('price');


        //    $overall_achive_unit_target=Product::join('leads', 'leads.item_id', '=', 'product.unitid')
        //                                ->where('product.status', '=', 'Sold')
        //                                ->where('leads.user_id', '=', $id)
        //                                ->count();

        //    $overall_achive_leads_target = Leads::where('user_id', $id)->count();

        //    $overall_achive_contacted_clients_target=Task::where('added_by', $id) // First where clause
        //                         ->where('type', 'Calls')
        //                         ->where(function ($query)
        //                         {
        //                             $query->where('subtype', 'Contacted Client')
        //                                 ->orWhere('subtype', 'Whatsapp Call');
        //                         })
        //                         ->count();


        //    $overall_achive_client_meetings_target  = Task::where('added_by', $id)->where('type', 'Meetings')->where('subtype', 'Meeting (Done)')->count();

        //    // echo '<pre>';print_r($overall_record->sum('set_unit_target')); exit;

        // // ========================Overall Record =================

        $user_target = array();

        $user_target =[
            'set_sales_target'                  => $set_sales_target,
            'set_unit_target'                   => $set_unit_target,
            'set_verified_calls_target'         => $set_verified_calls_target,
            'set_client_meetings_target'        => $set_client_meetings_target,
            // 'set_dealer_meetings_target'        => $set_dealer_meetings_target,
            // 'set_freelancer_meetings_target'    => $set_freelancer_meetings_target,
            'set_site_visit_target'             => $set_site_visit_target,

            'achive_sales_target'               => $achive_sales_target,
            'achive_unit_target'                => $achive_unit_target,
            'achive_unit_target_unitids'        => $achive_unit_target_unitids,
            'achive_verified_calls_target'      => $achive_verified_calls_target,
            'achive_client_meetings_target'     => $achive_client_meetings_target,
            // 'achive_dealer_meetings_target'     => $achive_dealer_meetings_target,
            // 'achive_freelancer_meetings_target' => $achive_freelancer_meetings_target,
            'achive_site_visit_target'          => $achive_site_visit_target,

            // Leads Graph
            'facebook_leads'    => $facebook_leads,
            'dealer_leads'      => $dealer_leads,
            'freelancer_leads'  => $freelancer_leads,
            'personal_leads'    => $personal_leads,
            'uan'               => $uan,
            'website_leads'     => $website_leads,


            //  Token
            'achive_number_of_token_target'   => $achive_number_of_token_target,

            // // today record
            // 'today_set_verified_calls_target' => $today_set_verified_calls_target,
            // 'today_set_client_meetings_target' => $today_set_client_meetings_target,

            // 'today_achive_contacted_clients_target' => $today_achive_contacted_clients_target,
            // 'today_achive_client_meetings_target'   => $today_achive_client_meetings_target,

            // New Leads
            'set_leads_target'                => $set_leads_target,
            'achive_new_leads_target'         => $achive_new_leads_target,

            // dealer
            'set_dealer_meetings_target'      => $set_dealer_meetings_target,
            'achive_dealer_meetings_target'   => $achive_dealer_meetings_target,
            // freelancer
            'set_freelancer_meetings_target'    => $set_freelancer_meetings_target,
            'achive_freelancer_meetings_target' => $achive_freelancer_meetings_target,
            // affiliator
            'set_affiliator_target'    => $set_affiliator_target,
            'achive_affiliator_target' => $achive_affiliator_target,
            'dealer'                   => $dealer,
            'freelancer'               => $freelancer,


            // // overall record of company
            // 'overall_set_sales_target'                  =>  $overall_set_sales_target ,
            // 'overall_set_unit_target'                   =>  $overall_set_unit_target ,
            // 'overall_set_lead_target'                   =>  $overall_set_lead_target ,
            // 'overall_set_affiliator_target'             =>  $overall_set_affiliator_target ,
            // 'overall_set_verified_calls_target'         =>  $overall_set_verified_calls_target ,
            // 'overall_set_client_meetings_target'        =>  $overall_set_client_meetings_target ,
            // 'overall_set_dealer_meetings_target'        =>  $overall_set_dealer_meetings_target ,
            // 'overall_set_freelancer_meetings_target'    =>  $overall_set_freelancer_meetings_target ,
            // 'overall_set_site_visit_target'             =>  $overall_set_site_visit_target ,

            // // Overall Achive
            // 'overall_achive_sales_target'               =>  $overall_achive_sales_target,
            // 'overall_achive_unit_target'                =>  $overall_achive_unit_target,
            // 'overall_achive_leads_target'               =>  $overall_achive_leads_target,
            // 'overall_achive_contacted_clients_target'   =>  $overall_achive_contacted_clients_target,
            // 'overall_achive_client_meetings_target'     =>  $overall_achive_client_meetings_target ,

        ];

        // ====== Users With Helper======
            $data = array(
                'id' => Auth::user()->id,
                'role' => Auth::user()->role,
            );
            $responce = Helper::users($data);
            $graph_users = $responce['users'];
            $account = $responce['account'];
        // ====== Users With Helper======

        if (isset($_POST['action']) && $_POST['action'] == 'PDF')
        {
            // echo 'PDF';exit;

            // return view('dashboard_invoice', compact('graph_users' , 'user_target' , 'leads_count', 'clients_count', 'affiliator_count', 'aaffiliator_count', 'todayleads', 'todayclients', 'projects', 'sources', 'account','tasks')); exit;

            $pdf = Pdf::loadView('dashboard_invoice', compact('graph_users' , 'user_target' , 'leads_count', 'clients_count', 'affiliator_count', 'aaffiliator_count', 'todayleads', 'todayclients', 'projects', 'sources', 'account','tasks'))
                    ->setPaper('a4')
                    ->setOption([
                        'tempDir' => public_path(),
                        'chroot' => public_path(),
                        'isPhpEnabled' => true,
                    ]);
            return $pdf->download('Invoice.pdf');
        }

        return view('home', compact('graph_users' , 'user_target' , 'leads_count', 'clients_count', 'affiliator_count', 'aaffiliator_count', 'todayleads', 'todayclients', 'projects', 'sources', 'account','tasks'));
    }


}

