<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserMetaTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create('user_meta', function (Blueprint $table) {
            $table->engine = 'MyISAM';
            $table->bigIncrements('id');
            $table->string('bname')->nullable();
            $table->string('tname')->nullable();
            $table->string('eyestablish')->nullable();
            $table->string('bnature')->nullable();
            $table->string('lentity')->nullable();
            $table->string('selling')->nullable();
            $table->string('bowner')->nullable();
            $table->string('website')->nullable();
            $table->string('vatnumber')->nullable();
            $table->string('regnumber')->nullable();
            $table->string('address1')->nullable();
            $table->string('address2')->nullable();
            $table->string('bcity')->nullable();
            $table->string('bzipcode')->nullable();
            $table->string('bstate')->nullable();
            $table->string('bcountry')->nullable();
            $table->string('saddress1')->nullable();
            $table->string('saddress2')->nullable();
            $table->string('scity')->nullable();
            $table->string('szipcode')->nullable();
            $table->string('sstate')->nullable();
            $table->string('scountry')->nullable();
            $table->string('comment')->nullable();
            $table->integer('user_id')->unsigned();
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::dropIfExists('user_meta');
    }

}
