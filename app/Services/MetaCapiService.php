<?php

namespace App\Services;

class MetaCapiService
{
    public function sendLeadEvent($client, $lead, $campaign, $token, $context = 'web')
    {
        $pixel_id = env('META_PIXEL_ID');

        $ip = $context === 'web' ? request()->ip() : null;
        $ua = $context === 'web' ? request()->userAgent() : null;

        $url = "https://graph.facebook.com/v20.0/{$pixel_id}/events?access_token={$token}";

        $phone = preg_replace('/[^0-9]/', '', $client->phone);

        $payload = [
            'data' => [[
                'event_name' => 'Lead',
                'event_time' => time(),
                'action_source' => 'website',

                'user_data' => [
                    'em' => hash('sha256', strtolower(trim($client->email))),
                    'ph' => hash('sha256', $phone),
                    'fn' => hash('sha256', strtolower(trim($client->name))),
                    'client_ip_address' => $ip,
                    'client_user_agent' => $ua,
                ],

                'custom_data' => [
                    'lead_id' => $lead->id,
                    'project_id' => $campaign->project_id,
                ]
            ]]
        ];

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json'
        ]);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $response = curl_exec($ch);

        if (curl_errno($ch)) {
            \Log::error('Meta CAPI Error: ' . curl_error($ch));
        } else {
            \Log::info('Meta CAPI Cron Response', json_decode($response, true));

            // ✅ (SUCCESS LOG)
            if (!empty($responseData['events_received'])) {
                \Log::info('Meta CAPI Cron Success', [
                    'lead_id' => $lead->id ?? null,   // if available in scope
                    'status' => 'sent',
                    'events_received' => $responseData['events_received']
                ]);
            }
        }

        curl_close($ch);
    }
}
