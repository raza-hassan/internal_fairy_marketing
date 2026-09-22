<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\FacebookTokenService;

class RefreshFacebookToken extends Command
{
    protected $signature = 'facebook:refresh-token';
    protected $description = 'Refresh Facebook long-lived token before it expires';

    public function handle(FacebookTokenService $tokenService)
    {
        $result = $tokenService->refreshExisting();

        if (!$result['success']) {
            $this->error('❌ ' . $result['message']);
            return Command::FAILURE;
        }

        if (!empty($result['skipped'])) {
            $this->info($result['message']);
            return Command::SUCCESS;
        }

        $this->info('✅ ' . $result['message']);
        return Command::SUCCESS;
    }
}
