<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('product_status_history', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('product_id');
            $table->string('from_status')->nullable();
            $table->string('to_status');
            // null changed_by = system/auto action (e.g. stale-hold auto-release), not a user.
            $table->unsignedBigInteger('changed_by')->nullable();
            $table->text('note')->nullable();
            $table->timestamp('created_at')->useCurrent();

            $table->index('product_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('product_status_history');
    }
};
