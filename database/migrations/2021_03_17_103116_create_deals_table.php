<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDealsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('deals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id');
            $table->unsignedSmallInteger('number_of_products');
            $table->unsignedInteger('price');
            $table->unsignedFloat('unit_price');
            $table->timestamps();

            $table->foreign('product_id')->references('id')->on('products');
            $table->unique(['product_id', 'number_of_products']);

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('deals');
    }
}
