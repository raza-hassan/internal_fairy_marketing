<?php

namespace App\Console\Commands;

use App\Http\Helpers\Helper;
use App\Models\Product;
use Carbon\Carbon;
use Illuminate\Console\Command;

class ProcessHoldExpiry extends Command
{
    protected $signature = 'inventory:process-hold-expiry';
    protected $description = 'Auto-release units still on Hold whose hold_expiary has passed, and warn holders 3 days before release';

    const WARNING_DAYS_BEFORE = 3;

    public function handle()
    {
        $now = Carbon::now()->timezone('Asia/Karachi');
        $warningThreshold = $now->copy()->addDays(self::WARNING_DAYS_BEFORE);

        // 1) Release units still on Hold/Token whose hold_expiary has already passed.
        // Only status = 'Hold' or 'Token' rows are touched — a unit with any payment against
        // it (Token, Partial Token, Sold Aprroval, ...) is never auto-released.
        $staleHolds = Product::whereIn('status', ['Hold', 'Token'])
            ->where('hold_status', 1)
            ->whereNotNull('hold_expiary')
            ->where('hold_expiary', '<=', $now)
            ->get();

        foreach ($staleHolds as $product) {

        $previousHoldBy = $product->hold_by;
            $expiredAt = $product->hold_expiary;

            $product->changeStatus(
                'Available',
                [
                    'hold_status' => 0,
                    'hold_expiary' => null,
                    'hold_by' => 0,
                    'sold_by' => null,
                    'sold_at' => null,
                    'is_approved' => 0,
                    'approved_by' => 0,
                ],
                null,
                'Auto-released: hold_expiary (' . $expiredAt . ') passed with no status update (held by user #' . $previousHoldBy . ')'
            );

            if ($previousHoldBy) {
                Helper::notification([
                    'type' => 'Inventory Auto-Released',
                    'msg_body' => base64_encode('Unit ID: ' . $product->unitid . ' auto-released to Available — hold expired.'),
                    'created_by' => $previousHoldBy,
                    'show_to' => $previousHoldBy,
                    'show_to_role' => 0,
                    'redirect' => 'inventory',
                ]);
            }

            $this->info("Released unit #{$product->id} ({$product->unitid}) — expired {$expiredAt}");
        }

        // 2) Warn holders whose units will expire within the next WARNING_DAYS_BEFORE days.
        $nearingExpiry = Product::where('status', 'Hold')
            ->where('hold_status', 1)
            ->whereNotNull('hold_expiary')
            ->where('hold_expiary', '>', $now)
            ->where('hold_expiary', '<=', $warningThreshold)
            ->whereNull('hold_warning_sent_at')
            ->get();

        foreach ($nearingExpiry as $product) {
            if (!$product->hold_by) {
                continue;
            }

            Helper::notification([
                'type' => 'Inventory Hold Expiring Soon',
                'msg_body' => base64_encode('Unit ID: ' . $product->unitid . ' will auto-release to Available on ' . $product->hold_expiary . ' if no status update is made.'),
                'created_by' => $product->hold_by,
                'show_to' => $product->hold_by,
                'show_to_role' => 0,
                'redirect' => 'inventory',
            ]);

            $product->hold_warning_sent_at = $now;
            $product->save();

            $this->info("Warned holder of unit #{$product->id} ({$product->unitid})");
        }

        \Log::info('inventory:process-hold-expiry finished', [
            'released' => $staleHolds->count(),
            'warned' => $nearingExpiry->count(),
        ]);
    }
}
