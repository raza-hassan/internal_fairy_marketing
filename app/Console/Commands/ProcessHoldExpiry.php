<?php

namespace App\Console\Commands;

use App\Http\Helpers\Helper;
use App\Models\Product;
use Carbon\Carbon;
use Illuminate\Console\Command;

class ProcessHoldExpiry extends Command
{
    protected $signature = 'inventory:process-hold-expiry';
    protected $description = 'Auto-release units stuck on Hold for 30 days with no status update, and warn holders 3 days before release';

    const HOLD_DAYS_LIMIT = 30;
    const WARNING_DAYS_BEFORE = 3;

    public function handle()
    {
        $now = Carbon::now()->timezone('Asia/Karachi');
        $releaseCutoff = $now->copy()->subDays(self::HOLD_DAYS_LIMIT);
        $warningCutoff = $now->copy()->subDays(self::HOLD_DAYS_LIMIT - self::WARNING_DAYS_BEFORE);

        // 1) Release units that have been on Hold since before the cutoff.
        $staleHolds = Product::where('status', 'Hold')
            ->whereNotNull('held_at')
            ->where('held_at', '<=', $releaseCutoff)
            ->get();

        foreach ($staleHolds as $product) {
            $previousHoldBy = $product->hold_by;
            $heldSince = $product->held_at;

            $product->changeStatus('Available', ['hold_status' => 0], null,
                'Auto-released: no status update for ' . self::HOLD_DAYS_LIMIT . ' days (held by user #' . $previousHoldBy . ' since ' . $heldSince . ')');

            if ($previousHoldBy) {
                Helper::notification([
                    'type' => 'Inventory Auto-Released',
                    'msg_body' => base64_encode('Unit ID: ' . $product->unitid . ' auto-released to Available — no status update for ' . self::HOLD_DAYS_LIMIT . ' days.'),
                    'created_by' => $previousHoldBy,
                    'show_to' => $previousHoldBy,
                    'show_to_role' => 0,
                    'redirect' => 'inventory',
                ]);
            }

            $this->info("Released unit #{$product->id} ({$product->unitid}) — held since {$heldSince}");
        }

        // 2) Warn holders whose units are close to the cutoff, once.
        $nearingExpiry = Product::where('status', 'Hold')
            ->whereNotNull('held_at')
            ->where('held_at', '<=', $warningCutoff)
            ->where('held_at', '>', $releaseCutoff)
            ->whereNull('hold_warning_sent_at')
            ->get();

        foreach ($nearingExpiry as $product) {
            if (!$product->hold_by) {
                continue;
            }

            Helper::notification([
                'type' => 'Inventory Hold Expiring Soon',
                'msg_body' => base64_encode('Unit ID: ' . $product->unitid . ' will auto-release to Available in ' . self::WARNING_DAYS_BEFORE . ' days if no status update is made.'),
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
