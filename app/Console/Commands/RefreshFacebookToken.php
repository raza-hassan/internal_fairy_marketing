<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use App\Models\Facebook;
use Carbon\Carbon;

class RefreshFacebookToken extends Command
{
    protected $signature = 'facebook:refresh-token';
    protected $description = 'Refresh Facebook long-lived token before it expires';

    public function handle()
    {
        $facebook = Facebook::first(); // get record from DB

        if (!$facebook) {
            $this->error('No Facebook record found.');
            return Command::FAILURE;
        }

        // Optional: check if token is about to expire (assuming you store expiry timestamp)
        if ($facebook->expires_at && now()->lt($facebook->expires_at->subDays(3))) {
            $this->info('Token still valid for more than 3 days. No refresh needed.');
            return Command::SUCCESS;
        }

        // Call Graph API to exchange token
        $response = Http::get("https://graph.facebook.com/v23.0/oauth/access_token", [
            'grant_type' => 'fb_exchange_token',
            'client_id' => $facebook->client_id,
            'client_secret' => $facebook->client_secret,
            'fb_exchange_token' =>  $facebook->long_lived_token, // if this expire then use below with updated token
            // 'fb_exchange_token' =>  'EAAGFa8OXdWUBPqcSFd6kRH7OeqxJIZBna4SkFnZCmTzLKhNTgavhSbXknCRwbkBEMoLAUkw81JAOdaDgOAfkgZC0xyzqWUoW9Hf43Bme3RJNF10qEVsIiwUAf9wZCPBiS9O5oZB7ZCkpioTo6Aif4vo6DCd4Mamc0kacYgL7lSC3c8n7kBrlh1BoNMZCxewaLh5TPZBhhbHMnatyaTu2QHCs5hOejX022gdkPQKx9KNG57feRGjF8yxZA',
        ]);

        if ($response->successful() || $response->ok()) {

            $data = $response->json();

            // if already Exist
            $facebook->long_lived_token = $data['access_token'];
            $facebook->expires_at = now()->addSeconds($data['expires_in']); // ~60 days
            $facebook->save();

            // Facebook::updateOrCreate(
            //     ['token_type' => 'user_long_lived'],
            //     [
            //         'client_id' => $facebook->client_id,
            //         'client_secret' => $facebook->client_secret,
            //         'page_id' => $facebook->page_id,
            //         'long_lived_token' => $data['access_token'],
            //         'expires_at' => Carbon::now()->addSeconds($data['expires_in']),
            //     ]
            // );


            $this->info('✅ Facebook token refreshed and saved to DB.');
            return Command::SUCCESS;
        }

        $this->error('❌ Failed to refresh token: ' . $response->body());
        return Command::FAILURE;
    }
}
