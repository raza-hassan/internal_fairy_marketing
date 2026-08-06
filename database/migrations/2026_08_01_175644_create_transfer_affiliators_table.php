<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up(): void
    {
        Schema::create('transfer_affiliators', function (Blueprint $table) {
            $table->id();

            // Affiliate being transferred
            $table->foreignId('affiliator_id')
                ->constrained('affiliators')
                ->cascadeOnDelete();

            // From user
            $table->foreignId('from_user_id')
                ->constrained('users')
                ->cascadeOnDelete();

            // To user
            $table->foreignId('to_user_id')
                ->constrained('users')
                ->cascadeOnDelete();

            // User who performed the transfer
            $table->foreignId('transferred_by')
                ->constrained('users')
                ->cascadeOnDelete();

            $table->tinyInteger('status')->default(0);

            $table->text('note')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('transfer_affiliators');
    }
};
