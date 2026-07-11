<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ModifyUsersMetaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('user_meta', function (Blueprint $table) {
//            $table->engine = 'MyISAM';
//            $table->bigIncrements('id');
            $table->string('bname')->nullable()->change();
            $table->string('tname')->nullable()->change();
            $table->string('eyestablish')->nullable()->change();
            $table->string('bnature')->nullable()->change();
            $table->string('lentity')->nullable()->change();
            $table->string('selling')->nullable()->change();
            $table->string('bowner')->nullable()->change();
            $table->string('website')->nullable()->change();
            $table->string('vatnumber')->nullable()->change();
            $table->string('regnumber')->nullable()->change();
            $table->string('address1')->nullable()->change();
            $table->string('address2')->nullable()->change();
            $table->string('bcity')->nullable()->change();
            $table->string('bzipcode')->nullable()->change();
            $table->string('bstate')->nullable()->change();
            $table->string('bcountry')->nullable()->change();
            $table->string('saddress1')->nullable()->change();
            $table->string('saddress2')->nullable()->change();
            $table->string('scity')->nullable()->change();
            $table->string('szipcode')->nullable()->change();
            $table->string('sstate')->nullable()->change();
            $table->string('scountry')->nullable()->change();
            $table->string('comment')->nullable()->change();
            $table->integer('user_id')->unsigned()->change();
//            $table->timestamps();
//
//            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
