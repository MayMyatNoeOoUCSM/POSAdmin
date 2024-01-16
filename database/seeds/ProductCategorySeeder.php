<?php

use Illuminate\Database\Seeder;

class ProductCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('m_product_category')->insert([
            [
                'product_id' => 1,
                'category_id'=> 1,
                'created_at' =>  date('Y-m-d H:i:s'),
                'updated_at' =>  date('Y-m-d H:i:s')
            ],
        ]);
        DB::table('m_product_category')->insert([
            [
                'product_id' => 2,
                'category_id'=> 2,
                'created_at' =>  date('Y-m-d H:i:s'),
                'updated_at' =>  date('Y-m-d H:i:s')
            ],
        ]);
        DB::table('m_product_category')->insert([
            [
                'product_id' => 3,
                'category_id'=> 3,
                'created_at' =>  date('Y-m-d H:i:s'),
                'updated_at' =>  date('Y-m-d H:i:s')
            ],
        ]);
    }
}
