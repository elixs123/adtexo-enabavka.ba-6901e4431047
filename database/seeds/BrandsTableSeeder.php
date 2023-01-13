<?php

use Composer\IO\NullIO;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

/**
 * Class BrandsTableSeeder
 */
class BrandsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $brands = array(
            array('id' => '6','name' => 'Samsung','logo' => null,'slug' => 'samsung','priority' => '1','status' => 'active','created_at' => '2019-12-13 14:25:48','updated_at' => '2019-12-13 14:25:48'),
            array('id' => '7','name' => 'iPhone','logo' => null,'slug' => 'iphone','priority' => '1','status' => 'active','created_at' => '2019-12-13 14:25:57','updated_at' => '2019-12-13 14:25:57')
          );          

        DB::table('brands')->insert($brands);
    }
}
