<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('product_import_settings', function (Blueprint $table) {
            $table->id();
            $table->decimal('down_payment_percent', 5, 4)->nullable();         // e.g. 0.2500
            $table->decimal('possession_percent', 5, 4)->nullable();           // e.g. 0.1000
            $table->unsignedInteger('monthly_installments_count')->nullable();
            $table->unsignedInteger('quarterly_installments_count')->nullable();
            $table->decimal('corner_amount', 15, 2)->nullable();               // flat Rs.
            $table->decimal('corner_percent', 5, 4)->nullable();
            $table->timestamps();
        });

        // Single settings row — sab null, matlab hardcoded defaults use hongi
        // jab tak koi is row ko update na kar de.
        DB::table('product_import_settings')->insert([
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('product_import_settings');
    }
};
