<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('product', function (Blueprint $table) {
            // Auto-release se pehle warning notification duplicate na bheji jaye,
            // is liye yaha mark kar dete hain. Expiry khud hold_expiary column
            // se decide hoti hai (7/14 din, token amount ke hisab se)
            $table->timestamp('hold_warning_sent_at')->nullable()->after('hold_status');
        });
    }

    public function down(): void
    {
        Schema::table('product', function (Blueprint $table) {
            $table->dropColumn(['hold_warning_sent_at']);
        });
    }
};
