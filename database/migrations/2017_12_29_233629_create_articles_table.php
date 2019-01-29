<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateArticlesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('articles', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('code');
            $table->string('title')->nullable();
            $table->text('body')->nullable();
            $table->text('barcodes')->nullable();
            $table->string('formattedPrice')->nullable();
            $table->string('price')->nullable();
            $table->string('category')->nullable();
            $table->string('supplementaryPriceLabel1')->nullable();
            $table->string('supplementaryPriceLabel2')->nullable();
            $table->string('shop')->nullable();
            $table->string('imageUrl')->nullable();
            $table->string('imageDefault')->nullable();
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
        Schema::dropIfExists('articles');
    }
}
