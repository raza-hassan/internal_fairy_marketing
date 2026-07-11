<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductsTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create('product', function (Blueprint $table) {
            $table->id();
            $table->string('name');
              $table->string('meta_title')->nullable();
            $table->string('meta_keyword')->nullable();
            $table->string('meta_description')->nullable();
            $table->string('sku')->nullable();
            $table->float('sprice')->nullable();
            $table->string('related')->nullable();
            $table->string('slug');
            $table->text('shortdesc')->nullable();
            $table->text('description')->nullable();
            $table->float('price')->default(0);
            $table->integer('stock')->default(0);
            $table->integer('status')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::dropIfExists('product');
    }

}
