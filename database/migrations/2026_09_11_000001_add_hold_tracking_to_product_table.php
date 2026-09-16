<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('product', function (Blueprint $table) {
            // Jab unit 'Hold' status me jati hai, yeh us waqt ka timestamp hai —
            // hold_expiary is liye reliable nahi (kahin "abhi" set ho jata hai,
            // kahin token-amount ke hisab se expiry date).
            $table->timestamp('held_at')->nullable()->after('hold_status');

            // Auto-release se pehle warning notification duplicate na bheji jaye,
            // is liye yaha mark kar dete hain.
            $table->timestamp('hold_warning_sent_at')->nullable()->after('held_at');
        });

        // Best-effort backfill: jo units abhi 'Hold' hain unke liye held_at ka
        // andaza updated_at se laga lete hain, taake purani holds bhi turant is
        // feature ke dayre me aa jayen (na ke sirf ab ke baad ki nayi holds).
        \DB::table('product')
            ->where('status', 'Hold')
            ->whereNull('held_at')
            ->update([
                // 'held_at' => \DB::raw('updated_at'),
                'updated_at' => now(),
                'held_at' => now(),
            ]);
    }

    public function down(): void
    {
        Schema::table('product', function (Blueprint $table) {
            $table->dropColumn(['held_at', 'hold_warning_sent_at']);
        });
    }
};
