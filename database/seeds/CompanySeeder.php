<?php

use Illuminate\Database\Seeder;

class CompanySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('m_company')->insert([
            [
                'name' => 'Company 1',
                'address' => 'YGN',
                'is_deleted' => config('constants.DEL_FLG_OFF'),
                'create_user_id' => 1,
                'update_user_id' => 1,
                'create_datetime' => date('Y-m-d H:i:s'),
                'update_datetime' => date('Y-m-d H:i:s'),
            ],
        ]);

        DB::table('m_company')->insert([
            [
                'name' => 'Company 2',
                'address' => 'MDY',
                'is_deleted' => config('constants.DEL_FLG_OFF'),
                'create_user_id' => 1,
                'update_user_id' => 1,
                'create_datetime' => date('Y-m-d H:i:s'),
                'update_datetime' => date('Y-m-d H:i:s'),
            ],
        ]);

        DB::table('m_company')->insert([
            [
                'name' => 'Company 3',
                'address' => 'BGO',
                'is_deleted' => config('constants.DEL_FLG_OFF'),
                'create_user_id' => 1,
                'update_user_id' => 1,
                'create_datetime' => date('Y-m-d H:i:s'),
                'update_datetime' => date('Y-m-d H:i:s'),
            ],
        ]);
    }
}
