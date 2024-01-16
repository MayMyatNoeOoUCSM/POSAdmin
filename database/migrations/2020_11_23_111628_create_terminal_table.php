<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTerminalTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('m_terminal', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('shop_id');
            $table->foreign('shop_id')->references('id')->on('m_shop');
            $table->string('name', 100)->nullable();
            $table->string('serial_number', 30)->default(0);
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
        Schema::dropIfExists('m_terminal');
    }
}
