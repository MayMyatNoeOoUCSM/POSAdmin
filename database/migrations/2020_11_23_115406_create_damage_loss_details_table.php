<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDamageLossDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('t_damage_loss_details', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('damage_loss_id');
            $table->foreign('damage_loss_id')->references('id')->on('t_damage_loss');
            $table->unsignedBigInteger('product_id');
            $table->foreign('product_id')->references('id')->on('m_product');
            $table->decimal('price', 18, 5);
            $table->integer('quantity');
            $table->tinyInteger('product_status');
            $table->string('remark', 500)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('t_damage_loss_details');
    }
}
