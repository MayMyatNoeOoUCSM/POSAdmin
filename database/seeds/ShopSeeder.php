<?php

use Illuminate\Database\Seeder;

class ShopSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('m_shop')->insert([
            [
                'company_id' => 1,
                'name' => 'Shop 1.1',
                'shop_type' => config('constants.RETAILS_SHOP'),
                'address'	=> 'YGN',
                'is_deleted' => 0,
                'create_user_id' => 1,
                'update_user_id' => 1,
                'create_datetime' => date('Y-m-d H:i:s'),
                'update_datetime' => date('Y-m-d H:i:s'),
            ],
        ]);

        DB::table('m_shop')->insert([
            [
                'company_id' => 1,
                'name' => 'Shop 1.2',
                'shop_type' => config('constants.RETAILS_SHOP'),
                'address'	=> 'YGN',
                'is_deleted' => 0,
                'create_user_id' => 1,
                'update_user_id' => 1,
                'create_datetime' => date('Y-m-d H:i:s'),
                'update_datetime' => date('Y-m-d H:i:s'),
            ],
        ]);

        DB::table('m_shop')->insert([
            [
                'company_id' => 2,
                'name' => 'Shop 2.1',
                'shop_type' => config('constants.RESTAURANT_SHOP'),
                'address'	=> 'MDY',
                'is_deleted' => 0,
                'create_user_id' => 1,
                'update_user_id' => 1,
                'create_datetime' => date('Y-m-d H:i:s'),
                'update_datetime' => date('Y-m-d H:i:s'),
            ],
        ]);

        DB::table('m_shop')->insert([
            [
                'company_id' => 2,
                'name' => 'Shop 2.2',
                'shop_type' => config('constants.RESTAURANT_SHOP'),
                'address'	=> 'MDY',
                'is_deleted' => 0,
                'create_user_id' => 1,
                'update_user_id' => 1,
                'create_datetime' => date('Y-m-d H:i:s'),
                'update_datetime' => date('Y-m-d H:i:s'),
            ],
        ]);
    }
}
