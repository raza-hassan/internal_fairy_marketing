<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLeadFeedbacksTable extends Migration
{
    public function up()
    {
        Schema::create('lead_feedbacks', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('lead_id');

            $table->enum('status', array_column(\App\Enums\LeadFeedBackStatus::cases(), 'value'));
            $table->text('remarks')->nullable();
            $table->unsignedDecimal('amount', 12, 2)->nullable();

            $table->unsignedBigInteger('created_by');

            $table->boolean('facebook_synced')->default(false);
            $table->timestamp('facebook_synced_at')->nullable();

            $table->timestamps();


            $table->index(['lead_id', 'status']);
            $table->index('created_by');
        });
    }

    public function down()
    {
        Schema::dropIfExists('lead_feedbacks');
    }
}
