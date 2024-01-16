<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCompanyLicenseTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('m_company_license', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('company_id');
            $table->date('start_date');
            $table->date('end_date');
            $table->tinyInteger('license_type');
            $table->integer('payment_amount');
            $table->integer('discount_amount')->default(0);
            $table->tinyInteger('status');
            $table->tinyInteger('user_count')->default(0);
            $table->tinyInteger('same_company_contact_flag');
            $table->string('contact_person', 50)->nullable();
            $table->string('contact_phone', 30)->nullable();
            $table->string('contact_email', 30)->nullable();
            $table->integer('create_user_id');
            $table->integer('update_user_id');
            $table->timestamp('create_datetime');
            $table->timestamp('update_datetime');

            $table->foreign('company_id')
                ->references('id')
                ->on('m_company')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('m_company_license');
    }
}
