<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('leads', function (Blueprint $table) {
            // Facebook Lead ID column add
            $table->string('facebook_lead_id')->nullable()->after('id');

            // Unique index (important for deduplication)
            $table->unique('facebook_lead_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('leads', function (Blueprint $table) {
            // Then drop column
            $table->dropColumn('facebook_lead_id');
        });
    }
};
