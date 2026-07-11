<?php

namespace App\Console\Commands;

use App\Http\Helpers\Helper;
use App\Models\Category;
use App\Models\Clients;
use App\Models\Compain;
use App\Models\Facebook;
use App\Models\Leads;
use App\Models\Number;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Services\FacebookLeadSyncService;
use Illuminate\Support\Facades\Log;

class DemoCron extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'stagging:cron';
    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';
    /**
     * Execute the console command.
     *
     * @return int
     */
    public function __construct()
    {
        parent::__construct();
    }

    public function handle(FacebookLeadSyncService $syncService)
    {
        Log::info('Facebook Lead Cron Started At: ' . now());

        $counter = 0;
        try {

            set_time_limit(300); // 5 minutes
            ini_set('max_execution_time', 300);

            $counter = $syncService->sync('cron');

            Log::info("Facebook Sync Completed. Leads Added: {$counter}");

            if ($counter > 0) {
                $roleUsers = User::whereIn('role', [13, 14])->get();
                foreach ($roleUsers as $roleUser) {
                    $data = array(
                        'type' => 'Added New Lead',
                        'msg_body' => 'New ' . $counter . ' Leads landed In CRM From FaceBook.',
                        'created_by' => 21,
                        'show_to' => $roleUser->id, // Show to role Users as a id
                        'show_to_role' => 0,
                        'redirect' => 'newleads',
                    );
                    Helper::notification($data);
                }

                $mailData = [
                    'name' => 'Facebook',
                    'counter' => $counter
                ];


                Mail::send('mail', $mailData, function ($message) {
                    $message->to([
                        'shahid.shahid34@gmail.com' => 'Shahid Iqbal',
                        'hassanraza74659@gmail.com' => 'Hassan Raza'
                    ])->subject('Fairy Marketing Facebook Lead Notification');
                    $message->from(config('mail.from.address'), Config('mail.from.name'));
                });
            }

            Log::info('Facebook Lead Cron Finished Successfully');
        } catch (\Exception $e) {

            Log::error('Facebook Lead Cron Failed', [
                'message' => $e->getMessage(),
                'line'    => $e->getLine(),
                'file'    => $e->getFile(),
            ]);
        }

        return Command::SUCCESS;
    }




    // public function handle()
    // {
    //     \Log::info('Stagging Cron executed at ' . now());

    //     $counter = 0;
    //     $facebook = Facebook::find(1);
    //     $campaigns = Compain::where('status', 1)->get();
    //     $ch = curl_init();

    //     $data = [
    //         'name' => 'Facebook',
    //         'counter' => 0
    //     ];

    //     foreach ($campaigns as $campaign) {
    //         curl_setopt($ch, CURLOPT_URL, 'https://graph.facebook.com/v20.0/' . $campaign->form_id . '/leads?access_token=' . $facebook->long_lived_token . '&fields=form_id,field_data');
    //         curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    //         $result = curl_exec($ch);
    //         $records = json_decode($result);

    //         // echo '<pre>';
    //         // print_r($records->data); //exit;

    //         $lead_id = 0;

    //         if (!empty($records->data) && is_array($records->data)) {
    //             foreach ($records->data as $record) {
    //                 if ($record->form_id != '') {
    //                     $lead_id = $this->leadsync($record, $campaign, $facebook->long_lived_token);

    //                     // echo $lead_id; exit;
    //                     if ($lead_id > 0) {
    //                         $counter++;
    //                     }
    //                 }
    //             }
    //         }
    //         // exit;
    //     }
    //     if (curl_errno($ch)) {
    //         echo 'Error:' . curl_error($ch);
    //     }

    //     if ($counter > 0) {
    //         $roleUsers = User::whereIn('role', [13, 14])->get();
    //         foreach ($roleUsers as $roleUser) {
    //             $data = array(
    //                 'type' => 'Added New Lead',
    //                 'msg_body' => 'New ' . $counter . ' Leads landed In CRM From FaceBook.',
    //                 'created_by' => 21,
    //                 'show_to' => $roleUser->id, // Show to role Users as a id
    //                 'show_to_role' => 0,
    //                 'redirect' => 'newleads',
    //             );
    //             Helper::notification($data);
    //         }
    //         $data['counter'] = $counter;

    //         Mail::send('mail', $data, function ($message) {
    //             $message->to([
    //                 'shahid.shahid34@gmail.com' => 'Shahid Iqbal',
    //                 'hassanraza74659@gmail.com' => 'Hassan Raza'
    //             ])->subject('Fairy Marketing Facebook Lead Notification');
    //             $message->from(config('mail.from.address'), Config('mail.from.name'));
    //         });
    //     }

    //     curl_close($ch);
    //     \Log::info("Stagging Cron is working fine With Face-Book Api !");
    //     // return 0;
    // }

    // public function leadsync($result, $campaign, $facebook_long_lived_token = null)
    // {
    //     $name = '';
    //     $email = '';
    //     $phone = '';
    //     $address = '';
    //     $lead_id = 0;
    //     $category_id = null;
    //     $check_phone = null;

    //     if (empty($result->field_data) || !is_array($result->field_data)) {
    //         return 0;
    //     }

    //     foreach ($result->field_data as $field) {

    //         $value = isset($field->values) && isset($field->values[0])
    //             ? trim($field->values[0])
    //             : '';

    //         if ($field->name == 'are_you_interested_in_shop_or_appartments') {

    //             $category_name = $value;
    //             $category_slug = strtolower(str_replace(' ', '-', $category_name));

    //             $category = Category::where('name', $category_name)
    //                 ->orWhere('slug', $category_name)
    //                 ->first();

    //             if ($category) {
    //                 $category_id = $category->id;
    //             } else {
    //                 $category = Category::create([
    //                     'name' => $category_name,
    //                     'slug' => $category_slug,
    //                     'description' => $category_name,
    //                 ]);

    //                 $category_id = $category->id;
    //             }
    //         } elseif (
    //             $field->name == 'full_name' ||
    //             $field->name == 'your_name' ||
    //             $field->name == 'full name'
    //         ) {

    //             $name = $value;
    //         } elseif ($field->name == 'email') {

    //             $email = $value;
    //         } elseif ($field->name == 'phone_number') {

    //             $phone = $value;

    //             $check_0 = mb_substr($phone, 0, 1);
    //             $check_plus_92 = mb_substr($phone, 0, 3);

    //             if ($check_0 == '0') {
    //                 $check_phone = mb_substr($phone, 1);
    //             } elseif ($check_plus_92 == '+92') {
    //                 $check_phone = mb_substr($phone, 3);
    //             } else {
    //                 $check_phone = $value;
    //             }
    //         } elseif ($field->name == 'city') {

    //             $address = $value;
    //         }
    //     }

    //     // $client = Clients::where('phone', $check_phone)->orWhere('email', $email)->first();
    //     // $client_record = Number::where('number', 'like', '%' . $check_phone . '%')->where('type', 'clients')->first();

    //     $client = null;
    //     $client_record = null;

    //     // if (!empty($check_phone)) { $client = Clients::where('phone', $check_phone)->first();}
    //     // elseif (!empty($email)) {$client = Clients::where('email', $email)->first(); }

    //     $client = Clients::where('phone', $check_phone)
    //         ->when(!empty($email), function ($query) use ($email) {
    //             $query->orWhere('email', $email);
    //         })
    //         ->first();

    //     if (!empty($check_phone)) {
    //         $client_record = Number::where('number', $check_phone)->where('type', 'clients')->first();
    //     }

    //     if (empty($client) && empty($client_record) && !empty($campaign)) {

    //         $attributes = ['phone' => $phone];
    //         if (!empty($email)) {
    //             $attributes['email'] = $email;
    //         }

    //         $client = Clients::firstOrCreate(
    //             $attributes,
    //             [
    //                 'name' => $name,
    //                 'email' => $email,
    //                 'phone' => $phone,
    //                 'address' => $address,
    //                 'source_id' => 3,
    //                 'office_id' => $campaign->office_id,
    //             ],

    //         );

    //         $lead = Leads::create([
    //             'client_id' => $client->id,
    //             'project_id' => $campaign->project_id,
    //             'source_id' => 3,
    //             'office_id' => $campaign->office_id,
    //             'category_id' => $category_id,
    //         ]);

    //         $lead_id = $lead->id;

    //         Number::insert([
    //             'number' => $phone,
    //             'type' => 'clients',
    //             'client_id' => $client->id,
    //         ]);

    //         app(\App\Services\MetaCapiService::class)
    //             ->sendLeadEvent(
    //                 $client,
    //                 $lead,
    //                 $campaign,
    //                 $facebook_long_lived_token,
    //                 'cron'
    //             );
    //     } else {

    //         if (!empty($client) && !empty($client_record) && !empty($campaign)) {

    //             $leads = Leads::where('client_id', $client->id)
    //                 ->where('project_id', $campaign->project_id)
    //                 ->get();

    //             if ($leads->isEmpty()) {

    //                 $lead = Leads::create([
    //                     'client_id' => $client->id,
    //                     'project_id' => $campaign->project_id,
    //                     'source_id' => 3,
    //                     'user_id' => $client->user_id,
    //                     'office_id' => $campaign->office_id,
    //                     'category_id' => $category_id,
    //                 ]);

    //                 $lead_id = $lead->id;

    //                 app(\App\Services\MetaCapiService::class)
    //                     ->sendLeadEvent(
    //                         $client,
    //                         $lead,
    //                         $campaign,
    //                         $facebook_long_lived_token,
    //                         'cron'
    //                     );
    //             }
    //         }
    //     }

    //     return $lead_id;
    // }
}
