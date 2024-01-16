<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(CompanySeeder::class);
        $this->call(CompanyLicenseSeeder::class);
        $this->call(ShopSeeder::class);
        $this->call(StaffTableSeeder::class);
        $this->call(TerminalSeeder::class);
        $this->call(CategorySeeder::class);
        $this->call(ProductSeeder::class);
        //$this->call(ProductCategorySeeder::class);
        $this->call(ShopCategorySeeder::class);
        $this->call(ShopProductSeeder::class);
        $this->call(RestaurantTableSeeder::class);
    }
}
