<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPaidAndChangeAmountInSaleTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('t_sale', function (Blueprint $table) {
            $table->decimal('paid_amount', 18, 5)->default(0);
            $table->decimal('change_amount', 18, 5)->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('t_sale', function (Blueprint $table) {
            $table->dropColumn('paid_amount');
            $table->dropColumn('change_amount');
        });
    }
}
