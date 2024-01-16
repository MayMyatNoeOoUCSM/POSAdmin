<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSaleReturnDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('t_return_details', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('return_id');
            $table->foreign('return_id')->references('id')->on('t_return');
            $table->unsignedBigInteger('product_id');
            $table->foreign('product_id')->references('id')->on('m_product');
            $table->decimal('price', 18, 5);
            $table->integer('quantity');
            $table->string('remark', 100)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('t_return_details');
    }
}
