<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductRatingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_ratings', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('product_id')->unsigned();
            $table->string('name', 255);
            $table->decimal('grade', 2);
            $table->text('comment');

            $table->timestamps();
            $table->foreign('product_id')->references('id')->on('products')->onupdate('cascade')->ondelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::create('product_ratings', function (Blueprint $table) {
            $table->dropForeign('product_ratings_product_id_foreign');
        });

        Schema::dropIfExists('product_ratings');
    }
}