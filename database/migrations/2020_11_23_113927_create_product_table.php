<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('m_product', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('company_id')->nullable();
            $table->foreign('company_id')->references('id')->on('m_company');
            $table->unsignedBigInteger('product_type_id');
            $table->foreign('product_type_id')->references('id')->on('m_category');
            $table->string('product_code', 150)->unique();
            $table->string('barcode', 150)->nullable();
            $table->string('name', 100);
            $table->string('short_name', 50)->nullable();
            $table->integer('sale_price')->default(0);
            $table->integer('minimum_quantity')->default(0);
            $table->string('description', 250)->nullable();
            $table->string('product_image_path', 255)->nullable();
            $table->date('mfd_date')->nullable();
            $table->date('expire_date')->nullable();
            $table->tinyInteger('product_status')->default(1);
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
        Schema::dropIfExists('m_product');
    }
}
