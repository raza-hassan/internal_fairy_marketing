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

        // $events = \App\Enums\LeadFeedbackStatus::cases();

        if (!$status->shouldSyncToFacebook()) {
            return;
        }

        $eventName = $status->facebookEvent();
        if (!$eventName) {
            return;
        }

        $response = $facebookFeedback->send(
            $eventName,
            $lead
        );


        // dd([
        //     'status' => $response->status(),
        //     'body' => $response->json(),
        // ]);

        if ($response->successful()) {

            $this->feedback->update([
                'facebook_synced' => true,
                'facebook_synced_at' => now()
            ]);
        }
    }
}
