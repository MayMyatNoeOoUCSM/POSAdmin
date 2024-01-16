<?php

use Illuminate\Database\Seeder;

class RestaurantTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('m_restaurant_table')->insert([
            [
                'shop_id'=> 3,
                'name' => 'Table 1',
                'total_seats_people' => 5,
                'available_status' => config('constants.RESTAURANT_TABLE_FREE'),
                'is_deleted' => config('constants.DEL_FLG_OFF'),
                'create_user_id' => 1,
                'update_user_id' => 1,
                'create_datetime' => date('Y-m-d H:i:s'),
                'update_datetime' => date('Y-m-d H:i:s'),
            ],
        ]);

        DB::table('m_restaurant_table')->insert([
            [
                'shop_id'=> 3,
                'name' => 'Table 2',
                'total_seats_people' => 5,
                'available_status' => config('constants.RESTAURANT_TABLE_FREE'),
                'is_deleted' => config('constants.DEL_FLG_OFF'),
                'create_user_id' => 1,
                'update_user_id' => 1,
                'create_datetime' => date('Y-m-d H:i:s'),
                'update_datetime' => date('Y-m-d H:i:s'),
            ],
        ]);

        DB::table('m_restaurant_table')->insert([
            [
                'shop_id'=> 3,
                'name' => 'Table 3',
                'total_seats_people' => 5,
                'available_status' => config('constants.RESTAURANT_TABLE_FREE'),
                'is_deleted' => config('constants.DEL_FLG_OFF'),
                'create_user_id' => 1,
                'update_user_id' => 1,
                'create_datetime' => date('Y-m-d H:i:s'),
                'update_datetime' => date('Y-m-d H:i:s'),
            ],
        ]);

        DB::table('m_restaurant_table')->insert([
            [
                'shop_id'=> 3,
                'name' => 'Table 4',
                'total_seats_people' => 5,
                'available_status' => config('constants.RESTAURANT_TABLE_FREE'),
                'is_deleted' => config('constants.DEL_FLG_OFF'),
                'create_user_id' => 1,
                'update_user_id' => 1,
                'create_datetime' => date('Y-m-d H:i:s'),
                'update_datetime' => date('Y-m-d H:i:s'),
            ],
        ]);

        DB::table('m_restaurant_table')->insert([
            [
                'shop_id'=> 3,
                'name' => 'Table 5',
                'total_seats_people' => 5,
                'available_status' => config('constants.RESTAURANT_TABLE_FREE'),
                'is_deleted' => config('constants.DEL_FLG_OFF'),
                'create_user_id' => 1,
                'update_user_id' => 1,
                'create_datetime' => date('Y-m-d H:i:s'),
                'update_datetime' => date('Y-m-d H:i:s'),
            ],
        ]);
    }
}
