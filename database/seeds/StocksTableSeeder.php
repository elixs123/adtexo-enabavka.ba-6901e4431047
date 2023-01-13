<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

/**
 * Class StocksTableSeeder
 */
class StocksTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $stocks = array(
            array('id' => '1','name' => 'Adtexo br. 1','address' => 'Skenderpasina 1','city' => 'Sarajevo','postal_code' => '71000','country_id' => 'bih','status' => 'active','created_at' => '2019-12-17 17:01:04','updated_at' => '2019-12-17 17:14:13'),
            array('id' => '2','name' => 'Adtexo br. 2','address' => 'Aleja Bosne Srebrene 201','city' => 'Sarajevo','postal_code' => '71000','country_id' => 'srb','status' => 'active','created_at' => '2019-12-17 17:01:14','updated_at' => '2019-12-17 17:14:25')
          );       

        DB::table('stocks')->insert($stocks);
    }
}
