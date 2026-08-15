<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\LeadFeedback;
use App\Services\FacebookConversionService;
use Illuminate\Support\Facades\Log;

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

            if ($response->successful()) {
                $this->feedback->update([
                    'facebook_synced' => true,
                    'facebook_synced_at' => now()
                ]);
            } else {
                Log::error('Facebook conversion event rejected', [
                    'feedback_id' => $this->feedback->id,
                    'lead_id' => $lead->id,
                    'event_name' => $eventName,
                    'http_status' => $response->status(),
                    'body' => $response->json(),
                ]);
            }
        } catch (\Throwable $e) {
            Log::error('Facebook conversion job failed', [
                'feedback_id' => $this->feedback->id,
                'lead_id' => $lead->id,
                'event_name' => $eventName,
                'message' => $e->getMessage(),
            ]);
        }
    }
}
