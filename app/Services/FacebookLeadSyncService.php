<?php

namespace App\Services;

use App\Models\Leads;
use App\Models\Clients;
use App\Models\Compain;
use App\Models\Facebook;
use App\Models\Category;
use App\Models\Number;
use Illuminate\Support\Facades\Log;

class FacebookLeadSyncService
{
    public function sync($context = 'web')
    {
        $counter = 0;
        $facebook = Facebook::find(1);

        if (!$facebook) {
            Log::error('Facebook settings not found');
            return 0;
        }

        $campaigns = Compain::where('status', 1)->get();

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 60);

        foreach ($campaigns as $campaign) {

            if (empty($campaign->form_id)) {
                continue;
            }

            $params = [
                'access_token' => $facebook->long_lived_token,
                'fields'       => 'id,form_id,field_data'
            ];

            if (!empty($campaign->last_synced_at)) {
                $params['since'] = strtotime($campaign->last_synced_at);
            }

            $url = 'https://graph.facebook.com/v20.0/' .
                $campaign->form_id .
                '/leads?' .
                http_build_query($params);

            do {

                curl_setopt($ch, CURLOPT_URL, $url);
                $result = curl_exec($ch);

                if (curl_errno($ch)) {

                    Log::error('Facebook CURL Error', [
                        'campaign_id' => $campaign->id,
                        'error' => curl_error($ch)
                    ]);

                    break;
                }

                $records = json_decode($result);

                if (isset($records->error)) {

                    Log::error('Facebook API Error', [
                        'campaign_id' => $campaign->id,
                        'error' => $records->error
                    ]);

                    break;
                }

                if (!empty($records->data)) {

                    foreach ($records->data as $record) {

                        if (Leads::where('facebook_lead_id', $record->id)->exists()) {
                            continue;
                        }

                        $leadId = $this->leadSync($record, $campaign, $facebook->long_lived_token, $context);

                        if ($leadId > 0) {
                            $counter++;
                        }
                    }
                }

                $url = $records->paging->next ?? null;
            } while ($url);

            $campaign->update([
                'last_synced_at' => now()
            ]);
        }

        curl_close($ch);

        return $counter;
    }

    public function leadsync($result, $campaign, $facebook_long_lived_token = null,  $context = 'web')
    {
        $name = '';
        $email = '';
        $phone = '';
        $address = '';
        $lead_id = 0;
        $category_id = null;
        $check_phone = null;

        // Skip if field_data missing
        if (
            empty($result->field_data) ||
            !is_array($result->field_data)
        ) {
            return 0;
        }

        foreach ($result->field_data as $field) {

            $value = isset($field->values) && isset($field->values[0])
                ? trim($field->values[0])
                : '';

            if ($field->name == 'are_you_interested_in_shop_or_appartments') {
                $category_name = $value;
                $category_slug = strtolower(str_replace(' ', '-', $category_name));

                $category = Category::where('name', $category_name)->orWhere('slug', $category_name)->first();
                if ($category) {
                    $category_id = $category->id;
                } else {
                    $category = Category::Create([
                        'name' => $category_name,
                        'slug' => $category_slug,
                        'description' => $category_name,
                    ]);
                    $category_id = $category->id;
                }
            } elseif ($field->name == 'full_name' || $field->name == 'your_name'  || $field->name == 'full name') {
                $name = $value;
            } elseif ($field->name == 'email') {
                $email = $value;
            } elseif ($field->name == 'phone_number') {
                $phone = $value;
                $check_0 = mb_substr($phone, 0, 1);         // Get the first characters for 0 check
                $check_plus_92 = mb_substr($phone, 0, 3);   // Get the first three characters for +92 check
                if ($check_0 == '0') {
                    $check_phone = mb_substr($phone, 1);    // Remove the first 0 character
                } elseif ($check_plus_92 == '+92') {
                    $check_phone = mb_substr($phone, 3);    // Remove the first three +92 characters
                } else {
                    $check_phone = $value;
                }
            } elseif ($field->name == 'city') {
                $address = $value;
            }
        }

        $client = null;
        $client_record = null;

        $client = Clients::where('phone', $check_phone)
            ->when(!empty($email), function ($query) use ($email) {
                $query->orWhere('email', $email);
            })
            ->first();

        if (!empty($check_phone)) {
            $client_record = Number::where('number', $check_phone)->where('type', 'clients')->first();
        }

        if (empty($client) && empty($client_record) && !empty($campaign)) {

            $attributes = ['phone' => $phone];
            if (!empty($email)) {
                $attributes['email'] = $email;
            }

            $client = Clients::firstOrCreate(
                $attributes,
                [
                    'name' => $name,
                    'email' => $email,
                    'phone' => $phone,
                    'address' => $address,
                    'source_id' => 3,
                    'office_id' => $campaign->office_id,
                ]
            );

            $lead = Leads::create([
                'client_id' => $client->id,
                'project_id' => $campaign->project_id,
                'source_id' => 3,
                'office_id' => $campaign->office_id,
                'category_id' => $category_id,
                'facebook_lead_id' => $result->id,

            ]);
            $lead_id = $lead->id;

            Number::insert(['number' => $phone, 'type' => 'clients', 'client_id' => $client->id,]);

            app(\App\Services\MetaCapiService::class)->sendLeadEvent($client, $lead, $campaign, $facebook_long_lived_token, $context);
        } else {
            if (!empty($client) && !empty($client_record) && !empty($campaign)) {
                $leads = Leads::where('client_id', $client['id'])->where('project_id', $campaign->project_id)->get();

                if ($leads->isEmpty()) {
                    $lead = Leads::create([
                        'client_id' => $client['id'],
                        'project_id' => $campaign->project_id,
                        'source_id' => 3,
                        'user_id' => $client['user_id'],
                        'office_id' => $campaign->office_id,
                        'category_id' => $category_id,
                        'facebook_lead_id' => $result->id,
                    ]);
                    $lead_id = $lead->id;

                    app(\App\Services\MetaCapiService::class)->sendLeadEvent($client, $lead, $campaign, $facebook_long_lived_token, $context);
                }
            }
        }

        return $lead_id;

    }
}
