<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductsTable extends Migration
{
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('product_name');
            $table->text('product_description')->default('No description added');
            $table->decimal('product_price', 10, 2);
            $table->string('product_category');
            $table->foreignId('attribute_id')->nullable();
            $table->timestamps();

           // $table->foreign('attribute_id')->references('id')->on('attributes')->onDelete('set null');
        });
    }

    public function down()
    {
        Schema::dropIfExists('products');
    }
}

