<?php

use Illuminate\Database\Seeder;

class CompanyLicenseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('m_company_license')->insert([
            [
                'company_id' => 1,
                'start_date' => '2020-01-01',
                'end_date' => '2021-12-31',
                'license_type' => 1,
                'payment_amount' => 100,
                'discount_amount' => 0,
                'status' => config('constants.COMPANY_LICENSE_ACTIVE'),
                'same_company_contact_flag' => 1,
                'create_user_id' => 1,
                'update_user_id' => 1,
                'create_datetime' => date('Y-m-d H:i:s'),
                'update_datetime' => date('Y-m-d H:i:s'),
            ],
        ]);
        DB::table('m_company_license')->insert([
            [
                'company_id' => 2,
                'start_date' => '2020-01-01',
                'end_date' => '2021-12-31',
                'license_type' => 1,
                'payment_amount' => 200,
                'discount_amount' => 0,
                'status' => config('constants.COMPANY_LICENSE_ACTIVE'),
                'same_company_contact_flag' => 1,
                'create_user_id' => 1,
                'update_user_id' => 1,
                'create_datetime' => date('Y-m-d H:i:s'),
                'update_datetime' => date('Y-m-d H:i:s'),
            ],
        ]);
        DB::table('m_company_license')->insert([
            [
                'company_id' => 3,
                'start_date' => '2020-01-01',
                'end_date' => '2020-12-31',
                'license_type' => 1,
                'payment_amount' => 300,
                'discount_amount' => 0,
                'status' => config('constants.COMPANY_LICENSE_EXPIRE'),
                'same_company_contact_flag' => 1,
                'create_user_id' => 1,
                'update_user_id' => 1,
                'create_datetime' => date('Y-m-d H:i:s'),
                'update_datetime' => date('Y-m-d H:i:s'),
            ],
        ]);
    }
}
