<?php

use Illuminate\Database\Seeder;

class StaffTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('m_staff')->insert([
            [
                'staff_number' => 'A001',
                'password' => bcrypt('A1234567'),
                'role' => config('constants.ADMIN'),
                'staff_type' => 1,
                'position' => config('constants.SYSTEM_ADMIN'),
                'graduated_univeristy' => 'graduated',
                'name' => 'admin',
                'gender' => 1,
                'nrc_number' => '12/BaThaHta(N)012345',
                'join_from' => date('Y-m-d'),
                'create_user_id' => 1,
                'update_user_id' => 1,
                'create_datetime' => date('Y-m-d H:i:s'),
                'update_datetime' => date('Y-m-d H:i:s'),
            ],
        ]);

        DB::table('m_staff')->insert([
            [
                'staff_number' => 'CA001',
                'password' => bcrypt('A1234567'),
                'company_id' => 1,
                'role' => config('constants.COMPANY_ADMIN'),
                'staff_type' => 1,
                'position' => config('constants.OWNER'),
                'graduated_univeristy' => 'graduated',
                'name' => 'company 1 admin',
                'gender' => 1,
                'nrc_number' => '12/BaThaHta(N)012345',
                'join_from' => date('Y-m-d'),
                'create_user_id' => 1,
                'update_user_id' => 1,
                'create_datetime' => date('Y-m-d H:i:s'),
                'update_datetime' => date('Y-m-d H:i:s'),
            ],
        ]);

        DB::table('m_staff')->insert([
            [
                'staff_number' => 'CA002',
                'password' => bcrypt('A1234567'),
                'company_id' => 2,
                'role' => config('constants.COMPANY_ADMIN'),
                'staff_type' => 1,
                'position' => config('constants.OWNER'),
                'graduated_univeristy' => 'graduated',
                'name' => 'company 2 admin',
                'gender' => 1,
                'nrc_number' => '12/BaThaHta(N)012345',
                'join_from' => date('Y-m-d'),
                'create_user_id' => 1,
                'update_user_id' => 1,
                'create_datetime' => date('Y-m-d H:i:s'),
                'update_datetime' => date('Y-m-d H:i:s'),
            ],
        ]);

        DB::table('m_staff')->insert([
            [
                'staff_number' => 'SA001',
                'password' => bcrypt('A1234567'),
                'company_id' => 1,
                'shop_id' => 1,
                'role' => config('constants.SHOP_ADMIN'),
                'staff_type' => 1,
                'position' => config('constants.MANAGER'),
                'graduated_univeristy' => 'graduated',
                'name' => 'shop 1.1 admin',
                'gender' => 1,
                'nrc_number' => '12/BaThaHta(N)012345',
                'join_from' => date('Y-m-d'),
                'create_user_id' => 1,
                'update_user_id' => 1,
                'create_datetime' => date('Y-m-d H:i:s'),
                'update_datetime' => date('Y-m-d H:i:s'),
            ],
        ]);

        DB::table('m_staff')->insert([
            [
                'staff_number' => 'SA002',
                'password' => bcrypt('A1234567'),
                'company_id' => 1,
                'shop_id' => 2,
                'role' => config('constants.SHOP_ADMIN'),
                'staff_type' => 1,
                'position' => config('constants.MANAGER'),
                'graduated_univeristy' => 'graduated',
                'name' => 'shop 1.2 admin',
                'gender' => 1,
                'nrc_number' => '12/BaThaHta(N)012345',
                'join_from' => date('Y-m-d'),
                'create_user_id' => 1,
                'update_user_id' => 1,
                'create_datetime' => date('Y-m-d H:i:s'),
                'update_datetime' => date('Y-m-d H:i:s'),
            ],
        ]);

        DB::table('m_staff')->insert([
            [
                'staff_number' => 'SA003',
                'password' => bcrypt('A1234567'),
                'company_id' => 1,
                'shop_id' => 3,
                'role' => config('constants.SHOP_ADMIN'),
                'staff_type' => 1,
                'position' => config('constants.MANAGER'),
                'graduated_univeristy' => 'graduated',
                'name' => 'shop 2.1 admin',
                'gender' => 1,
                'nrc_number' => '12/BaThaHta(N)012345',
                'join_from' => date('Y-m-d'),
                'create_user_id' => 1,
                'update_user_id' => 1,
                'create_datetime' => date('Y-m-d H:i:s'),
                'update_datetime' => date('Y-m-d H:i:s'),
            ],
        ]);

        DB::table('m_staff')->insert([
            [
                'staff_number' => 'SS001',
                'password' => bcrypt('A1234567'),
                'company_id' => 1,
                'shop_id' => 1,
                'role' => config('constants.SALE_STAFF'),
                'staff_type' => 1,
                'position' => config('constants.OPERATION_STAFF'),
                'graduated_univeristy' => 'graduated',
                'name' => 'shop 1.1 sale staff',
                'gender' => 1,
                'nrc_number' => '12/BaThaHta(N)012345',
                'join_from' => date('Y-m-d'),
                'create_user_id' => 1,
                'update_user_id' => 1,
                'create_datetime' => date('Y-m-d H:i:s'),
                'update_datetime' => date('Y-m-d H:i:s'),
            ],
        ]);
        DB::table('m_staff')->insert([
            [
                'staff_number' => 'CS001',
                'password' => bcrypt('A1234567'),
                'company_id' => 2,
                'shop_id' => 3,
                'role' => config('constants.CASHIER_STAFF'),
                'staff_type' => 1,
                'position' => config('constants.OPERATION_STAFF'),
                'graduated_univeristy' => 'graduated',
                'name' => 'shop 2.1 cashier',
                'gender' => 1,
                'nrc_number' => '12/BaThaHta(N)012345',
                'join_from' => date('Y-m-d'),
                'create_user_id' => 1,
                'update_user_id' => 1,
                'create_datetime' => date('Y-m-d H:i:s'),
                'update_datetime' => date('Y-m-d H:i:s'),
            ],
        ]);

        DB::table('m_staff')->insert([
            [
                'staff_number' => 'KS001',
                'password' => bcrypt('A1234567'),
                'company_id' => 2,
                'shop_id' => 3,
                'role' => config('constants.KITCHEN_STAFF'),
                'staff_type' => 1,
                'position' => config('constants.OPERATION_STAFF'),
                'graduated_univeristy' => 'graduated',
                'name' => 'shop 2.1 kitchen',
                'gender' => 1,
                'nrc_number' => '12/BaThaHta(N)012345',
                'join_from' => date('Y-m-d'),
                'create_user_id' => 1,
                'update_user_id' => 1,
                'create_datetime' => date('Y-m-d H:i:s'),
                'update_datetime' => date('Y-m-d H:i:s'),
            ],
        ]);

        DB::table('m_staff')->insert([
            [
                'staff_number' => 'WS001',
                'password' => bcrypt('A1234567'),
                'company_id' => 2,
                'shop_id' => 3,
                'role' => config('constants.WAITER_STAFF'),
                'staff_type' => 1,
                'position' => config('constants.OPERATION_STAFF'),
                'graduated_univeristy' => 'graduated',
                'name' => 'shop 2.1 waiter',
                'gender' => 1,
                'nrc_number' => '12/BaThaHta(N)012345',
                'join_from' => date('Y-m-d'),
                'create_user_id' => 1,
                'update_user_id' => 1,
                'create_datetime' => date('Y-m-d H:i:s'),
                'update_datetime' => date('Y-m-d H:i:s'),
            ],
        ]);
    }
}
