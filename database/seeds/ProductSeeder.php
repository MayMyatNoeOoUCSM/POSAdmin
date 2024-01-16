<?php

use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('m_product')->insert([
            [
                'company_id' => 1,
                'product_type_id' => 1,
                'product_code' => '88301010000001',
                'name' => 'Samsung TV',
                'short_name' => 's-tv',
                'sale_price' => 100000,
                'product_status'    => config('constants.PRODUCT_ACTIVE'),
                'create_user_id' => 1,
                'update_user_id' => 1,
                'create_datetime' => date('Y-m-d H:i:s'),
                'update_datetime' => date('Y-m-d H:i:s'),
                'product_image_path' => 'uploads/products/01/01/000/0001.jfif'
            ],
        ]);

        DB::table('m_product')->insert([
            [
                'company_id' => 1,
                'product_type_id' => 1,
                'product_code' => '88301010000002',
                'name' => 'LG TV',
                'short_name' => 'lg-tv',
                'sale_price' => 200000,
                'product_status'    => config('constants.PRODUCT_ACTIVE'),
                'create_user_id' => 1,
                'update_user_id' => 1,
                'create_datetime' => date('Y-m-d H:i:s'),
                'update_datetime' => date('Y-m-d H:i:s'),
                'product_image_path' => 'uploads/products/01/01/000/0002.jfif'
            ],
        ]);

        DB::table('m_product')->insert([
            [
                'company_id' => 1,
                'product_type_id' => 1,
                'product_code' => '88301010000003',
                'name' => 'Hisense TV',
                'short_name' => 'h-tv',
                'sale_price' => 300000,
                'product_status'    => config('constants.PRODUCT_ACTIVE'),
                'create_user_id' => 1,
                'update_user_id' => 1,
                'create_datetime' => date('Y-m-d H:i:s'),
                'update_datetime' => date('Y-m-d H:i:s'),
                'product_image_path' => 'uploads/products/01/01/000/0003.jpg'

            ],
        ]);

        DB::table('m_product')->insert([
            [
                'company_id' => 1,
                'product_type_id' => 1,
                'product_code' => '88301010000004',
                'name' => 'Skyworth TV',
                'short_name' => 'sky-tv',
                'sale_price' => 400000,
                'product_status'    => config('constants.PRODUCT_ACTIVE'),
                'create_user_id' => 1,
                'update_user_id' => 1,
                'create_datetime' => date('Y-m-d H:i:s'),
                'update_datetime' => date('Y-m-d H:i:s'),
                'product_image_path' => 'uploads/products/01/01/000/0004.jpg'

            ],
        ]);

        DB::table('m_product')->insert([
            [
                'company_id' => 1,
                'product_type_id' => 1,
                'product_code' => '88301010000005',
                'name' => 'Panasonic TV',
                'short_name' => 'panasonic-tv',
                'sale_price' => 500000,
                'product_status'    => config('constants.PRODUCT_ACTIVE'),
                'create_user_id' => 1,
                'update_user_id' => 1,
                'create_datetime' => date('Y-m-d H:i:s'),
                'update_datetime' => date('Y-m-d H:i:s'),
                'product_image_path' => 'uploads/products/01/01/000/0005.jfif'

            ],
        ]);

        DB::table('m_product')->insert([
            [
                'company_id' => 1,
                'product_type_id' => 3,
                'product_code' => '88301030000001',
                'name' => 'HP Laptop',
                'short_name' => 'hp-laptop',
                'sale_price' => 1500000,
                'product_status'    => config('constants.PRODUCT_ACTIVE'),
                'create_user_id' => 1,
                'update_user_id' => 1,
                'create_datetime' => date('Y-m-d H:i:s'),
                'update_datetime' => date('Y-m-d H:i:s'),
                'product_image_path' => 'uploads/products/01/03/000/0001.png'
            ],
        ]);

        DB::table('m_product')->insert([
            [
                'company_id' => 1,
                'product_type_id' => 3,
                'product_code' => '88301030000002',
                'name' => 'Lenovo Laptop',
                'short_name' => 'lenovo-laptop',
                'sale_price' => 1200000,
                'product_status'    => config('constants.PRODUCT_ACTIVE'),
                'create_user_id' => 1,
                'update_user_id' => 1,
                'create_datetime' => date('Y-m-d H:i:s'),
                'update_datetime' => date('Y-m-d H:i:s'),
                'product_image_path' => 'uploads/products/01/03/000/0002.jpg'
            ],
        ]);

        DB::table('m_product')->insert([
            [
                'company_id' => 1,
                'product_type_id' => 3,
                'product_code' => '88301030000003',
                'name' => 'Acer Laptop',
                'short_name' => 'acer-laptop',
                'sale_price' => 1000000,
                'product_status'    => config('constants.PRODUCT_ACTIVE'),
                'create_user_id' => 1,
                'update_user_id' => 1,
                'create_datetime' => date('Y-m-d H:i:s'),
                'update_datetime' => date('Y-m-d H:i:s'),
                'product_image_path' => 'uploads/products/01/03/000/0003.jfif'

            ],
        ]);

        DB::table('m_product')->insert([
            [
                'company_id' => 1,
                'product_type_id' => 3,
                'product_code' => '88301030000004',
                'name' => 'Msi Laptop',
                'short_name' => 'msi-laptop',
                'sale_price' => 1100000,
                'product_status'    => config('constants.PRODUCT_ACTIVE'),
                'create_user_id' => 1,
                'update_user_id' => 1,
                'create_datetime' => date('Y-m-d H:i:s'),
                'update_datetime' => date('Y-m-d H:i:s'),
                'product_image_path' => 'uploads/products/01/03/000/0004.png'
            ],
        ]);

        DB::table('m_product')->insert([
            [
                'company_id' => 1,
                'product_type_id' => 3,
                'product_code' => '88301030000005',
                'name' => 'Huawei Laptop',
                'short_name' => 'huawei-laptop',
                'sale_price' => 1100000,
                'product_status'    => config('constants.PRODUCT_ACTIVE'),
                'create_user_id' => 1,
                'update_user_id' => 1,
                'create_datetime' => date('Y-m-d H:i:s'),
                'update_datetime' => date('Y-m-d H:i:s'),
                'product_image_path' => 'uploads/products/01/03/000/0005.jpg'

            ],
        ]);

        DB::table('m_product')->insert([
            [
                'company_id' => 1,
                'product_type_id' => 8,
                'product_code' => '88301080000001',
                'name' => 'Y2O',
                'short_name' => 'y2o',
                'sale_price' => 100000,
                'product_status'    => config('constants.PRODUCT_ACTIVE'),
                'create_user_id' => 1,
                'update_user_id' => 1,
                'create_datetime' => date('Y-m-d H:i:s'),
                'update_datetime' => date('Y-m-d H:i:s'),
                'product_image_path' => 'uploads/products/01/08/000/0001.jfif'
            ],
        ]);

        DB::table('m_product')->insert([
            [
                'company_id' => 1,
                'product_type_id' => 8,
                'product_code' => '88301080000002',
                'name' => 'Techno',
                'short_name' => 'techno',
                'sale_price' => 500000,
                'product_status'    => config('constants.PRODUCT_ACTIVE'),
                'create_user_id' => 1,
                'update_user_id' => 1,
                'create_datetime' => date('Y-m-d H:i:s'),
                'update_datetime' => date('Y-m-d H:i:s'),
                'product_image_path' => 'uploads/products/01/08/000/0002.png'
            ],
        ]);

        DB::table('m_product')->insert([
            [
                'company_id' => 1,
                'product_type_id' => 8,
                'product_code' => '88301080000003',
                'name' => 'Iphone',
                'short_name' => 'iphone',
                'sale_price' => 700000,
                'product_status'    => config('constants.PRODUCT_ACTIVE'),
                'create_user_id' => 1,
                'update_user_id' => 1,
                'create_datetime' => date('Y-m-d H:i:s'),
                'update_datetime' => date('Y-m-d H:i:s'),
                'product_image_path' => 'uploads/products/01/08/000/0003.jpg'
            ],
        ]);

        DB::table('m_product')->insert([
            [
                'company_id' => 1,
                'product_type_id' => 8,
                'product_code' => '88301080000004',
                'name' => 'Redmi',
                'short_name' => 'redmi',
                'sale_price' => 400000,
                'product_status'    => config('constants.PRODUCT_ACTIVE'),
                'create_user_id' => 1,
                'update_user_id' => 1,
                'create_datetime' => date('Y-m-d H:i:s'),
                'update_datetime' => date('Y-m-d H:i:s'),
                'product_image_path' => 'uploads/products/01/08/000/0004.jpg'
            ],
        ]);

        DB::table('m_product')->insert([
            [
                'company_id' => 1,
                'product_type_id' => 8,
                'product_code' => '88301080000005',
                'name' => 'Oppo',
                'short_name' => 'oppo',
                'sale_price' => 300000,
                'product_status'    => config('constants.PRODUCT_ACTIVE'),
                'create_user_id' => 1,
                'update_user_id' => 1,
                'create_datetime' => date('Y-m-d H:i:s'),
                'update_datetime' => date('Y-m-d H:i:s'),
                'product_image_path' => 'uploads/products/01/08/000/0005.jfif'
            ],
        ]);

        DB::table('m_product')->insert([
            [
                'company_id' => 2,
                'product_type_id' => 10,
                'product_code' => '88302100000001',
                'name' => 'Hamburger',
                'short_name' => 'hamburger',
                'sale_price' => 10000,
                'product_status'    => config('constants.PRODUCT_ACTIVE'),
                'create_user_id' => 1,
                'update_user_id' => 1,
                'create_datetime' => date('Y-m-d H:i:s'),
                'update_datetime' => date('Y-m-d H:i:s'),
                'product_image_path' => 'uploads/products/02/10/000/0001.jpg'
            ],
        ]);

        DB::table('m_product')->insert([
            [
                'company_id' => 2,
                'product_type_id' => 10,
                'product_code' => '88302100000002',
                'name' => 'Pizza',
                'short_name' => 'pizza',
                'sale_price' => 15000,
                'product_status'    => config('constants.PRODUCT_ACTIVE'),
                'create_user_id' => 1,
                'update_user_id' => 1,
                'create_datetime' => date('Y-m-d H:i:s'),
                'update_datetime' => date('Y-m-d H:i:s'),
                'product_image_path' => 'uploads/products/02/10/000/0002.jpg'

            ],
        ]);

        DB::table('m_product')->insert([
            [
                'company_id' => 2,
                'product_type_id' => 10,
                'product_code' => '88302100000003',
                'name' => 'Sandwich',
                'short_name' => 'sandwich',
                'sale_price' => 20000,
                'product_status'    => config('constants.PRODUCT_ACTIVE'),
                'create_user_id' => 1,
                'update_user_id' => 1,
                'create_datetime' => date('Y-m-d H:i:s'),
                'update_datetime' => date('Y-m-d H:i:s'),
                'product_image_path' => 'uploads/products/02/10/000/0003.jfif'

            ],
        ]);
        DB::table('m_product')->insert([
            [
                'company_id' => 2,
                'product_type_id' => 10,
                'product_code' => '88302100000004',
                'name' => 'Milk shake',
                'short_name' => 'milk-shake',
                'sale_price' => 10000,
                'product_status'    => config('constants.PRODUCT_ACTIVE'),
                'create_user_id' => 1,
                'update_user_id' => 1,
                'create_datetime' => date('Y-m-d H:i:s'),
                'update_datetime' => date('Y-m-d H:i:s'),
                'product_image_path' => 'uploads/products/02/10/000/0004.jfif'
            ],
        ]);
        DB::table('m_product')->insert([
            [
                'company_id' => 2,
                'product_type_id' => 10,
                'product_code' => '88302100000005',
                'name' => 'Muffin',
                'short_name' => 'muffin',
                'sale_price' => 20000,
                'product_status'    => config('constants.PRODUCT_ACTIVE'),
                'create_user_id' => 1,
                'update_user_id' => 1,
                'create_datetime' => date('Y-m-d H:i:s'),
                'update_datetime' => date('Y-m-d H:i:s'),
                'product_image_path' => 'uploads/products/02/10/000/0005.jfif'
            ],
        ]);

        DB::table('m_product')->insert([
            [
                'company_id' => 2,
                'product_type_id' => 9,
                'product_code' => '88302090000001',
                'name' => 'Charcoal Grills',
                'short_name' => 'Charcoal Grills',
                'sale_price' => 20000,
                'product_status'    => config('constants.PRODUCT_ACTIVE'),
                'create_user_id' => 1,
                'update_user_id' => 1,
                'create_datetime' => date('Y-m-d H:i:s'),
                'update_datetime' => date('Y-m-d H:i:s'),
            ],
        ]);

        DB::table('m_product')->insert([
            [
                'company_id' => 2,
                'product_type_id' => 9,
                'product_code' => '88302090000002',
                'name' => 'Charcoal Kettle Grills',
                'short_name' => 'Charcoal Kettle Grills',
                'sale_price' => 35000,
                'product_status'    => config('constants.PRODUCT_ACTIVE'),
                'create_user_id' => 1,
                'update_user_id' => 1,
                'create_datetime' => date('Y-m-d H:i:s'),
                'update_datetime' => date('Y-m-d H:i:s'),
            ],
        ]);

        DB::table('m_product')->insert([
            [
                'company_id' => 2,
                'product_type_id' => 9,
                'product_code' => '88302090000003',
                'name' => 'Kamado Grills',
                'short_name' => 'Kamado Grills',
                'sale_price' => 20000,
                'product_status'    => config('constants.PRODUCT_ACTIVE'),
                'create_user_id' => 1,
                'update_user_id' => 1,
                'create_datetime' => date('Y-m-d H:i:s'),
                'update_datetime' => date('Y-m-d H:i:s'),
            ],
        ]);

        DB::table('m_product')->insert([
            [
                'company_id' => 2,
                'product_type_id' => 9,
                'product_code' => '88302090000004',
                'name' => 'Pellet Grills',
                'short_name' => 'Pellet Grills',
                'sale_price' => 30000,
                'product_status'    => config('constants.PRODUCT_ACTIVE'),
                'create_user_id' => 1,
                'update_user_id' => 1,
                'create_datetime' => date('Y-m-d H:i:s'),
                'update_datetime' => date('Y-m-d H:i:s'),
            ],
        ]);

        DB::table('m_product')->insert([
            [
                'company_id' => 2,
                'product_type_id' => 9,
                'product_code' => '88302090000005',
                'name' => 'Gas and Propane Grills',
                'short_name' => 'Gas and Propane Grills',
                'sale_price' => 40000,
                'product_status'    => config('constants.PRODUCT_ACTIVE'),
                'create_user_id' => 1,
                'update_user_id' => 1,
                'create_datetime' => date('Y-m-d H:i:s'),
                'update_datetime' => date('Y-m-d H:i:s'),
            ],
        ]);
    }
}
