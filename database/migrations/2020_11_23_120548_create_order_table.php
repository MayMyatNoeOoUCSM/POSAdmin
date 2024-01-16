<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrderTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('t_order', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('invoice_number', 150)->unique();
            $table->unsignedBigInteger('terminal_id');
            $table->foreign('terminal_id')->references('id')->on('m_terminal');
            $table->unsignedBigInteger('shop_id');
            $table->foreign('shop_id')->references('id')->on('m_shop');
            $table->unsignedBigInteger('restaurant_table_id');
            $table->foreign('restaurant_table_id')->references('id')->on('m_restaurant_table');
            $table->date('order_date');
            $table->string('remark', 100)->nullable();
            $table->decimal('amount', 18, 5);
            $table->decimal('amount_tax', 18, 5);
            $table->tinyInteger('order_status')->default(1);
            $table->integer('print_count')->default(0);
            $table->string('reason', 500)->nullable();
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
        Schema::dropIfExists('t_order');
    }
}
