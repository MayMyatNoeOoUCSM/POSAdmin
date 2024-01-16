<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStaffTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('m_staff', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('old_id')->default(0);
            $table->unsignedBigInteger('company_id')->nullable();
            $table->foreign('company_id')->references('id')->on('m_company');
            $table->unsignedBigInteger('warehouse_id')->nullable();
            $table->foreign('warehouse_id')->references('id')->on('m_warehouse');
            $table->unsignedBigInteger('shop_id')->nullable();
            $table->foreign('shop_id')->references('id')->on('m_shop');
            $table->string('staff_number', 100)->unique();
            $table->string('password', 100)->nullable();
            $table->tinyInteger('role');
            $table->tinyInteger('staff_type');
            $table->tinyInteger('position');
            $table->string('bank_account_number', 100)->nullable();
            $table->string('graduated_univeristy', 250)->nullable();
            $table->string('name', 100);
            $table->tinyInteger('gender');
            $table->string('nrc_number', 100);
            $table->date('dob')->nullable();
            $table->tinyInteger('marital_status')->default(1);
            $table->string('race', 100)->nullable();
            $table->string('city', 100)->nullable();
            $table->string('address', 200)->nullable();
            $table->string('photo', 200)->nullable();
            $table->string('phone_number_1', 50)->nullable();
            $table->string('phone_number_2', 50)->nullable();
            $table->date('join_from');
            $table->date('join_to')->nullable();
            $table->tinyInteger('staff_status')->default(1);
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
        Schema::dropIfExists('m_staff');
    }
}
