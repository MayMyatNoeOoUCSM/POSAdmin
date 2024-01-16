<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSaleReturnTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('t_return', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('shop_id')->nullable();
            $table->foreign('shop_id')->references('id')->on('m_shop');
            $table->string('return_invoice_number', 150)->unique();
            $table->unsignedBigInteger('sale_id');
            $table->foreign('sale_id')->references('id')->on('t_sale');
            $table->date('return_date');
            $table->tinyInteger('is_deleted')->default(0);
            $table->integer('create_user_id');
            $table->integer('update_user_id');
            $table->timestamp('create_datetime');
            $table->timestamp('update_datetime');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('t_return');
    }
}
