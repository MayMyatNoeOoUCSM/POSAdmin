<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductCategoryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Schema::create('m_product_category', function (Blueprint $table) {
        //     $table->id();
        //     $table->unsignedBigInteger('product_id');
        //     $table->unsignedBigInteger('category_id');
        //     $table->timestamps();

        //     $table->unique(['product_id', 'category_id']);
        //     $table->foreign('product_id')
        //         ->references('id')
        //         ->on('m_product')
        //         ->onDelete('cascade');
        //     $table->foreign('category_id')
        //         ->references('id')
        //         ->on('m_category')
        //         ->onDelete('cascade');
        // });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //Schema::dropIfExists('m_product_category');
    }
}
