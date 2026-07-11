<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use App\Models\Facebook;
use App\Enums\LeadFeedBackStatus;

class FacebookConversionService
{
    public function send($eventName, $lead)
    {
        $pixelId = config('facebook.pixel_id');
        $facebook = Facebook::find(1);
        $feedback = $lead->feedbacks()->latest()->first();

        if (!$pixelId) {
            throw new \Exception('Facebook Pixel ID missing');
        }

        if (!$facebook || !$facebook->long_lived_token) {
            throw new \Exception('Facebook token not found');
        }

        // dd([
        //     'lead_id' => $lead->id,
        //     'feedback_count' => $lead->feedbacks()->count(),
        //     'feedbacks' => $lead->feedbacks()->latest()->first()
        // ]);

        $phone = $lead->client?->phone
            ?? $lead->client?->telephone
            ?? $lead->client?->telephone1;


        $customData = [
            'lead_id' => $lead->id,
            'facebook_lead_id' => $lead->facebook_lead_id ?? '',
            'feedback_id' => $feedback->id,
            'status' => $feedback->status->value ?? '',
            'remarks' => $feedback->remarks ?? '',
        ];

        // Purchase event requires value and currency
        if ($eventName == LeadFeedBackStatus::SALE_CLOSED->facebookEvent() ) {

            if (!$feedback || $feedback->amount == null) {
                throw new \Exception('Amount is required for Purchase event');
            }

            $customData['currency'] = 'PKR';
            $customData['value'] = (float) ($feedback->amount ?? 0);
        }


        $payload = [
            'data' => [
                [
                    'event_name' => $eventName,
                    'event_time' => time(),
                    'event_id' => 'feedback_' . $feedback->id,

                    'action_source' => 'system_generated',
                    // 'action_source' => 'website',

                    'custom_data' => $customData,

                    'user_data' => [

                        'external_id' => !empty($lead->facebook_lead_id) ? [
                            hash('sha256', $lead->facebook_lead_id)
                        ] : null,

                        'em' => !empty($lead->client?->email)
                            ? [hash('sha256', strtolower(trim($lead->client->email)))]
                            : null,

                        'ph' => !empty($phone)
                            ? [hash('sha256', preg_replace('/\D/', '', $phone))]
                            : null,

                    ]
                ]
            ],
            // 'test_event_code' => 'TEST63413'
        ];

        return Http::post(
            "https://graph.facebook.com/v23.0/{$pixelId}/events?access_token={$facebook->long_lived_token}",
            $payload
        );
    }
}
