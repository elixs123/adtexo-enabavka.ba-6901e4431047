<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

/**
 * Class ProductsTableSeeder
 */
class ProductsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

		/* `adtexo`.`products` */
		$products = array(
		  array('id' => '1','code' => 'P4652','photo' => NULL,'video' => NULL,'barcode' => '123456789','brand_id' => '6','category_id' => '11','weight' => '1200','length' => '100','width' => '45','height' => '2','loyalty_points' => '0','is_gratis' => '0','status' => 'active','rang' => '1','created_at' => '2020-01-24 10:55:56','updated_at' => '2020-01-24 10:55:56'),
		  array('id' => '2','code' => 'FSN.M.28313','photo' => NULL,'video' => NULL,'barcode' => '3875001780001','brand_id' => '6','category_id' => '11','weight' => '500','length' => NULL,'width' => NULL,'height' => NULL,'loyalty_points' => '0','is_gratis' => '0','status' => 'active','rang' => '1','created_at' => '2020-01-24 10:58:16','updated_at' => '2020-01-24 10:58:16')
		);
    

        DB::table('products')->insert($products);
		
		
		/* `adtexo`.`product_translations` */
		$product_translations = array(
		  array('id' => '1','product_id' => '1','lang_id' => 'bs','name' => 'Podmetači za auto','text' => NULL,'search' => 'P4652 Adtexo > Proizvodi > Auto Samsung 123456789','link' => '/bs/shop/routes.product/podmetaci-za-auto-p4652/1','created_at' => '2020-01-24 10:55:56','updated_at' => '2020-01-24 10:55:56'),
		  array('id' => '2','product_id' => '2','lang_id' => 'bs','name' => 'Aditiv za dizel','text' => NULL,'search' => 'FSN.M.28313 Adtexo > Proizvodi > Auto Samsung 3875001780001','link' => '/bs/shop/routes.product/aditiv-za-dizel-fsnm28313/2','created_at' => '2020-01-24 10:58:16','updated_at' => '2020-01-24 10:58:16')
		);
		
        DB::table('product_translations')->insert($product_translations);
		
		
		/* `adtexo`.`product_prices` */
		$product_prices = array(
		  array('product_id' => '1','country_id' => 'bih','mpc' => '58.50','vpc' => '50.00','mpc_eur' => '25.00','vpc_eur' => '20.00','created_at' => '2020-01-24 10:55:56','updated_at' => NULL),
		  array('product_id' => '1','country_id' => 'srb','mpc' => '500.00','vpc' => '400.00','mpc_eur' => '250.00','vpc_eur' => '200.00','created_at' => '2020-01-24 10:55:56','updated_at' => NULL),
		  array('product_id' => '2','country_id' => 'bih','mpc' => '15.00','vpc' => '12.12','mpc_eur' => '7.00','vpc_eur' => '8.00','created_at' => '2020-01-24 10:58:16','updated_at' => NULL),
		  array('product_id' => '2','country_id' => 'srb','mpc' => '0.00','vpc' => '0.00','mpc_eur' => '0.00','vpc_eur' => '0.00','created_at' => '2020-01-24 10:58:16','updated_at' => NULL)
		);

		
        DB::table('product_prices')->insert($product_prices);

		/* `adtexo`.`product_stocks` */
		$product_stocks = array(
		  array('id' => '1','product_id' => '2','stock_id' => '1','qty' => '1000.00','action' => '','note' => NULL,'created_at' => '2020-01-24 11:02:10','updated_at' => '2020-01-24 11:02:10'),
		  array('id' => '2','product_id' => '2','stock_id' => '2','qty' => '50.00','action' => '','note' => NULL,'created_at' => '2020-01-24 11:02:16','updated_at' => '2020-01-24 11:02:16'),
		  array('id' => '3','product_id' => '2','stock_id' => '1','qty' => '-2.00','action' => '','note' => NULL,'created_at' => '2020-01-24 11:02:22','updated_at' => '2020-01-24 11:02:22'),
		  array('id' => '4','product_id' => '1','stock_id' => '1','qty' => '15.00','action' => '','note' => NULL,'created_at' => '2020-01-24 11:02:34','updated_at' => '2020-01-24 11:02:34')
		);

		
        DB::table('product_stocks')->insert($product_stocks);		

		
    }
}
