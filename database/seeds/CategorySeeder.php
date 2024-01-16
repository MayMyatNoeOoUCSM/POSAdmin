<?php

use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('m_category')->insert([
            [
                'company_id' => 1,
                'name' => 'TV & Video',
                'is_deleted'=> config('constants.DEL_FLG_OFF'),
                'create_user_id' => 1,
                'update_user_id' => 1,
                'create_datetime' => date('Y-m-d H:i:s'),
                'update_datetime' => date('Y-m-d H:i:s'),
            ],
        ]);

        DB::table('m_category')->insert([
            [
                'company_id' => 1,
                'name' => 'Audio & Home Theater',
                'is_deleted'=> config('constants.DEL_FLG_OFF'),
                'create_user_id' => 1,
                'update_user_id' => 1,
                'create_datetime' => date('Y-m-d H:i:s'),
                'update_datetime' => date('Y-m-d H:i:s'),
            ],
        ]);

        DB::table('m_category')->insert([
            [
                'company_id' => 1,
                'name' => 'Computers',
                'is_deleted'=> config('constants.DEL_FLG_OFF'),
                'create_user_id' => 1,
                'update_user_id' => 1,
                'create_datetime' => date('Y-m-d H:i:s'),
                'update_datetime' => date('Y-m-d H:i:s'),
            ],
        ]);

        DB::table('m_category')->insert([
            [
                'company_id' => 1,
                'name' => 'Camera & Photo',
                'is_deleted'=> config('constants.DEL_FLG_OFF'),
                'create_user_id' => 1,
                'update_user_id' => 1,
                'create_datetime' => date('Y-m-d H:i:s'),
                'update_datetime' => date('Y-m-d H:i:s'),
            ],
        ]);

        DB::table('m_category')->insert([
            [
                'company_id' => 1,
                'name' => 'Wearable Technology',
                'is_deleted'=> config('constants.DEL_FLG_OFF'),
                'create_user_id' => 1,
                'update_user_id' => 1,
                'create_datetime' => date('Y-m-d H:i:s'),
                'update_datetime' => date('Y-m-d H:i:s'),
            ],
        ]);

        DB::table('m_category')->insert([
            [
                'company_id' => 1,
                'name' => 'Car Electronics & GPS',
                'is_deleted'=> config('constants.DEL_FLG_OFF'),
                'create_user_id' => 1,
                'update_user_id' => 1,
                'create_datetime' => date('Y-m-d H:i:s'),
                'update_datetime' => date('Y-m-d H:i:s'),
            ],
        ]);

        DB::table('m_category')->insert([
            [
                'company_id' => 1,
                'name' => 'Portable Audio',
                'is_deleted'=> config('constants.DEL_FLG_OFF'),
                'create_user_id' => 1,
                'update_user_id' => 1,
                'create_datetime' => date('Y-m-d H:i:s'),
                'update_datetime' => date('Y-m-d H:i:s'),
            ],
        ]);

        DB::table('m_category')->insert([
            [
                'company_id' => 1,
                'name' => 'Cell Phones',
                'is_deleted'=> config('constants.DEL_FLG_OFF'),
                'create_user_id' => 1,
                'update_user_id' => 1,
                'create_datetime' => date('Y-m-d H:i:s'),
                'update_datetime' => date('Y-m-d H:i:s'),
            ],
        ]);

        

        DB::table('m_category')->insert([
            [
                'company_id' => 2,
                'name' => 'BBQ',
                'is_deleted'=> config('constants.DEL_FLG_OFF'),
                'create_user_id' => 1,
                'update_user_id' => 1,
                'create_datetime' => date('Y-m-d H:i:s'),
                'update_datetime' => date('Y-m-d H:i:s'),
            ],
        ]);

        DB::table('m_category')->insert([
            [
                'company_id' => 2,
                'name' => 'Fast food',
                'is_deleted'=> config('constants.DEL_FLG_OFF'),
                'create_user_id' => 1,
                'update_user_id' => 1,
                'create_datetime' => date('Y-m-d H:i:s'),
                'update_datetime' => date('Y-m-d H:i:s'),
            ],
        ]);

        DB::table('m_category')->insert([
            [
                'company_id' => 2,
                'name' => 'Fast casual',
                'is_deleted'=> config('constants.DEL_FLG_OFF'),
                'create_user_id' => 1,
                'update_user_id' => 1,
                'create_datetime' => date('Y-m-d H:i:s'),
                'update_datetime' => date('Y-m-d H:i:s'),
            ],
        ]);
        DB::table('m_category')->insert([
            [
                'company_id' => 2,
                'name' => 'Casual dining',
                'is_deleted'=> config('constants.DEL_FLG_OFF'),
                'create_user_id' => 1,
                'update_user_id' => 1,
                'create_datetime' => date('Y-m-d H:i:s'),
                'update_datetime' => date('Y-m-d H:i:s'),
            ],
        ]);

        DB::table('m_category')->insert([
            [
                'company_id' => 2,
                'name' => 'Premium casual.',
                'is_deleted'=> config('constants.DEL_FLG_OFF'),
                'create_user_id' => 1,
                'update_user_id' => 1,
                'create_datetime' => date('Y-m-d H:i:s'),
                'update_datetime' => date('Y-m-d H:i:s'),
            ],
        ]);

        DB::table('m_category')->insert([
            [
                'company_id' => 2,
                'name' => 'Family style',
                'is_deleted'=> config('constants.DEL_FLG_OFF'),
                'create_user_id' => 1,
                'update_user_id' => 1,
                'create_datetime' => date('Y-m-d H:i:s'),
                'update_datetime' => date('Y-m-d H:i:s'),
            ],
        ]);
        DB::table('m_category')->insert([
            [
                'company_id' => 2,
                'name' => 'Fine dining',
                'is_deleted'=> config('constants.DEL_FLG_OFF'),
                'create_user_id' => 1,
                'update_user_id' => 1,
                'create_datetime' => date('Y-m-d H:i:s'),
                'update_datetime' => date('Y-m-d H:i:s'),
            ],
        ]);
        DB::table('m_category')->insert([
            [
                'company_id' => 2,
                'name' => 'Brasserie and bistro',
                'is_deleted'=> config('constants.DEL_FLG_OFF'),
                'create_user_id' => 1,
                'update_user_id' => 1,
                'create_datetime' => date('Y-m-d H:i:s'),
                'update_datetime' => date('Y-m-d H:i:s'),
            ],
        ]);
    }
}
