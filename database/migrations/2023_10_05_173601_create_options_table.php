<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOptionsTable extends Migration
{
    public function up()
    {
        Schema::create('options', function (Blueprint $table) {
            $table->id();
            $table->foreignId('attribute_id');
            $table->string('option_name');
            $table->decimal('additional_price', 10, 2);
            $table->timestamps();

           // $table->foreign('attribute_id')->references('id')->on('attributes')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('options');
    }
}
