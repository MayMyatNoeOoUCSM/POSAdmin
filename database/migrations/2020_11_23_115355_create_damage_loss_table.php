<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDamageLossTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('t_damage_loss', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('warehouse_id')->nullable();
            $table->foreign('warehouse_id')->references('id')->on('m_warehouse');
            $table->unsignedBigInteger('shop_id')->nullable();
            $table->foreign('shop_id')->references('id')->on('m_shop');
            $table->unsignedBigInteger('return_id')->nullable();
            $table->foreign('return_id')->references('id')->on('t_return');
            $table->date('damage_loss_date');
            $table->string('remark', 100)->nullable();
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
        Schema::dropIfExists('t_damage_loss');
    }
}
