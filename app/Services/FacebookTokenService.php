<?php

namespace App\Services;

use App\Models\Facebook;
use Illuminate\Support\Facades\Http;

class FacebookTokenService
{
    protected string $graphVersion = 'v20.0';

    public function getToken(): ?Facebook
    {
        return Facebook::first();
    }

    /**
     * Exchange a short-lived / freshly generated user access token (pasted by an admin
     * from the Facebook Graph API Explorer or App Dashboard) for a long-lived token,
     * and persist it as the single facebook_tokens record used by the rest of the app.
     */
    public function exchangeToken(string $shortLivedToken, ?string $clientId = null, ?string $clientSecret = null, ?string $pageId = null): array
    {
        $existing = $this->getToken();

        $clientId = $clientId ?: (string) optional($existing)->client_id;
        $clientSecret = $clientSecret ?: (string) optional($existing)->client_secret;
        $pageId = $pageId ?: (string) optional($existing)->page_id;

        if (empty($clientId) || empty($clientSecret)) {
            return [
                'success' => false,
                'message' => 'App ID and App Secret are required (either saved previously or provided in this form).',
            ];
        }

        $response = Http::get("https://graph.facebook.com/{$this->graphVersion}/oauth/access_token", [
            'grant_type' => 'fb_exchange_token',
            'client_id' => $clientId,
            'client_secret' => $clientSecret,
            'fb_exchange_token' => $shortLivedToken,
        ]);

        if (!$response->successful()) {
            return [
                'success' => false,
                'message' => 'Facebook rejected the token exchange: ' . $this->extractErrorMessage($response),
            ];
        }

        $data = $response->json();

        if (empty($data['access_token'])) {
            return [
                'success' => false,
                'message' => 'Facebook did not return an access token.',
            ];
        }

        $facebook = Facebook::updateOrCreate(
            ['id' => 1],
            [
                'client_id' => $clientId,
                'client_secret' => $clientSecret,
                'page_id' => $pageId,
                'long_lived_token' => $data['access_token'],
                'token_type' => $data['token_type'] ?? 'bearer',
                'expires_at' => now()->addSeconds($data['expires_in'] ?? (60 * 24 * 3600)),
            ]
        );

        return [
            'success' => true,
            'message' => 'Facebook token updated successfully. Expires at ' . $facebook->expires_at->format('Y-m-d H:i'),
            'facebook' => $facebook,
        ];
    }

    /**
     * Self-refresh the currently stored long-lived token before it expires, using the
     * same client_id/client_secret already on file. Used by both the scheduled command
     * and the manual "Refresh Now" button.
     */
    public function refreshExisting(bool $force = false): array
    {
        $facebook = $this->getToken();

        if (!$facebook) {
            return [
                'success' => false,
                'message' => 'No Facebook token has been saved yet. Paste a token below to set one up.',
            ];
        }

        if (!$force && $facebook->expires_at && now()->lt($facebook->expires_at->subDays(3))) {
            return [
                'success' => true,
                'skipped' => true,
                'message' => 'Token still valid for more than 3 days. No refresh needed.',
                'facebook' => $facebook,
            ];
        }

        return $this->exchangeToken($facebook->long_lived_token, $facebook->client_id, $facebook->client_secret, $facebook->page_id);
    }

    protected function extractErrorMessage($response): string
    {
        $error = $response->json('error.message');

        return $error ?: $response->body();
    }
}
