<?php

use Illuminate\Database\Seeder;

class TerminalSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('m_terminal')->insert([
            [
                'shop_id' => 1,
                'name' => 'Terminal One',
                'serial_number' => 0001,
                'is_deleted' => config('constants.DEL_FLG_OFF'),
                'create_user_id' => 1,
                'update_user_id' => 1,
                'create_datetime' => date('Y-m-d H:i:s'),
                'update_datetime' => date('Y-m-d H:i:s'),
            ],
        ]);
        DB::table('m_terminal')->insert([
            [
                'shop_id' => 2,
                'name' => 'Terminal Two',
                'serial_number' => 0002,
                'is_deleted' => config('constants.DEL_FLG_OFF'),
                'create_user_id' => 1,
                'update_user_id' => 1,
                'create_datetime' => date('Y-m-d H:i:s'),
                'update_datetime' => date('Y-m-d H:i:s'),
            ],
        ]);
        DB::table('m_terminal')->insert([
            [
                'shop_id' => 3,
                'name' => 'Terminal Three',
                'serial_number' => 0003,
                'is_deleted' => config('constants.DEL_FLG_OFF'),
                'create_user_id' => 1,
                'update_user_id' => 1,
                'create_datetime' => date('Y-m-d H:i:s'),
                'update_datetime' => date('Y-m-d H:i:s'),
            ],
        ]);
        DB::table('m_terminal')->insert([
            [
                'shop_id' => 4,
                'name' => 'Terminal Four',
                'serial_number' => 0004,
                'is_deleted' => config('constants.DEL_FLG_OFF'),
                'create_user_id' => 1,
                'update_user_id' => 1,
                'create_datetime' => date('Y-m-d H:i:s'),
                'update_datetime' => date('Y-m-d H:i:s'),
            ],
        ]);
    }
}
