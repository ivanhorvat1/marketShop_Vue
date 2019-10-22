<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDisActionSalesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('dis_action_sales', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('code');
            $table->string('title')->nullable();
            $table->text('body')->nullable();
            $table->text('barcodes')->nullable();
            $table->string('toDate')->nullable()->default('nedostupno');
            $table->string('formattedPrice')->nullable();
            $table->integer('price')->nullable();
            $table->string('oldPrice')->nullable()->default(0);
            $table->string('category')->nullable();
            $table->string('supplementaryPriceLabel1')->nullable();
            $table->string('supplementaryPriceLabel2')->nullable();
            $table->string('shop')->nullable();
            $table->string('imageUrl')->nullable();
            $table->string('imageDefault')->nullable();
            $table->boolean('deleted')->default(0);
            $table->timestamps();
            $table->charset = 'utf8mb4';
            $table->collate = 'utf8mb4_swedish_ci';
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('dis_action_sales');
    }
}
