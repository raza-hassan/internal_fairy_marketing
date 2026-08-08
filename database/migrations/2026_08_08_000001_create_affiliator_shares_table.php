<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('affiliator_shares', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('affiliator_id');
            $table->unsignedBigInteger('user_id');   // the user this affiliator is shared WITH
            $table->unsignedBigInteger('shared_by')->nullable(); // who performed the share (audit trail, mirrors transferred_by on transfer_affiliators)
            $table->timestamps();

            $table->foreign('affiliator_id')->references('id')->on('affiliators')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('shared_by')->references('id')->on('users')->onDelete('set null');

            // prevent the same affiliator being shared with the same user twice
            $table->unique(['affiliator_id', 'user_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('affiliator_shares');
    }
};
