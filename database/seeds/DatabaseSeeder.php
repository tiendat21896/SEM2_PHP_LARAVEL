<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
//    public function run()
//    {
//        // $this->call(UserSeeder::class);
//        factory(\App\Brand::class, 5) ->create();
//    }
//    public function run1()
//    {
//        // $this->call(UserSeeder::class);
//        factory(\App\Category::class, 5) ->create();
//    }
    public function run()
    {
        // $this->call(UserSeeder::class);
        factory(\App\Product::class, 20) ->create();
    }
}
