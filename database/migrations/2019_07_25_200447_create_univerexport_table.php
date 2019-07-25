<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUniverexportTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('univerexports', function (Blueprint $table) {
            $table->integer('id');
            $table->string('name')->nullable();
            $table->string('price_old')->default(0)->nullable();
            $table->integer('price_new')->nullable();
            $table->string('price_measure')->nullable();
            $table->string('price_reference')->nullable();
            $table->string('reference_measure')->nullable();
            $table->string('manufacturer')->nullable();
            $table->string('image_url')->nullable();
            $table->string('category')->nullable();
            $table->string('url')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('univerexport');
    }
}
