<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAttributesTable extends Migration
{
    public function up()
    {
        Schema::create('attributes', function (Blueprint $table) {
            $table->id();
            $table->string('attribute_name');
            $table->string('selection_type');
            $table->integer('minimum_options')->nullable();
            $table->integer('maximum_options')->nullable();
            $table->foreignId('product_id');
            $table->timestamps();

            // Define foreign key relationship with the products table
            //$table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('attributes');
    }
}
