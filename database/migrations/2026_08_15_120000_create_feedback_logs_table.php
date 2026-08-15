<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFeedbackLogsTable extends Migration
{
    public function up()
    {
        Schema::create('feedback_logs', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('lead_feedback_id');
            $table->unsignedBigInteger('lead_id');

            $table->string('event_name')->nullable();
            $table->boolean('success')->default(false);
            $table->unsignedSmallInteger('http_status')->nullable();
            $table->unsignedInteger('events_received')->nullable();
            $table->json('response')->nullable();
            $table->text('error_message')->nullable();

            $table->timestamps();

            $table->foreign('lead_feedback_id')->references('id')->on('lead_feedbacks')->cascadeOnDelete();
            $table->index(['lead_feedback_id', 'success']);
            $table->index('lead_id');
        });
    }

    public function down()
    {
        Schema::dropIfExists('feedback_logs');
    }
}
