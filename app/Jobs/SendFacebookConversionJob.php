<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\LeadFeedback;
use App\Models\FeedbackLog;
use App\Services\FacebookConversionService;

class SendFacebookConversionJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $feedback;

    public function __construct(LeadFeedback $feedback)
    {

        $this->feedback = $feedback;
    }

    public function handle(FacebookConversionService $facebookFeedback)
    {
        $lead = $this->feedback->lead;
        $status = $this->feedback->status;

        if (!$status->shouldSyncToFacebook()) {
            return;
        }

        $eventName = $status->facebookEvent();
        if (!$eventName) {
            return;
        }

        try {
            $response = $facebookFeedback->send(
                $eventName,
                $lead
            );

            $body = $response->json();
            $eventsReceived = $body['events_received'] ?? null;
            $success = $response->successful() && $eventsReceived > 0;

            FeedbackLog::create([
                'lead_feedback_id' => $this->feedback->id,
                'lead_id' => $lead->id,
                'event_name' => $eventName,
                'success' => $success,
                'http_status' => $response->status(),
                'events_received' => $eventsReceived,
                'response' => $body,
            ]);

            if ($success) {
                $this->feedback->update([
                    'facebook_synced' => true,
                    'facebook_synced_at' => now()
                ]);
            }
        } catch (\Throwable $e) {
            FeedbackLog::create([
                'lead_feedback_id' => $this->feedback->id,
                'lead_id' => $lead->id,
                'event_name' => $eventName,
                'success' => false,
                'error_message' => $e->getMessage(),
            ]);
        }
    }
}
