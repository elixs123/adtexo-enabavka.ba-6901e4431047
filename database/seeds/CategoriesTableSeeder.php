<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

/**
 * Class CategoriesTableSeeder
 */
class CategoriesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $categories = array(
            array('id' => '1','father_id' => '0','list_of_parents' => '1','priority' => '1','status' => 'active','lft' => '1','rgt' => '8','created_at' => '2019-12-13 13:13:39','updated_at' => '2019-12-13 14:23:38'),
            array('id' => '10','father_id' => '1','list_of_parents' => '1,10','priority' => '1','status' => 'active','lft' => '2','rgt' => '7','created_at' => '2019-12-13 14:22:44','updated_at' => '2019-12-13 14:23:38'),
            array('id' => '11','father_id' => '10','list_of_parents' => '1,10,11','priority' => '1','status' => 'active','lft' => '3','rgt' => '4','created_at' => '2019-12-13 14:23:29','updated_at' => '2019-12-13 14:23:38'),
            array('id' => '12','father_id' => '10','list_of_parents' => '1,10,12','priority' => '1','status' => 'active','lft' => '5','rgt' => '6','created_at' => '2019-12-13 14:23:38','updated_at' => '2019-12-13 14:23:38')
          );                 

        DB::table('categories')->insert($categories);

        $category_translations = array(
            array('id' => '1','category_id' => '1','lang_id' => 'bs','name' => 'Adtexo','description' => NULL,'slug' => 'adtexo','path' => 'Adtexo','created_at' => '2019-12-13 13:14:06','updated_at' => '2019-12-13 13:14:06'),
            array('id' => '10','category_id' => '1','lang_id' => 'en','name' => 'Adtexo','description' => '','slug' => 'adtexo','path' => 'Adtexo','created_at' => '2019-12-13 14:11:41','updated_at' => '2019-12-13 14:11:41'),
            array('id' => '12','category_id' => '1','lang_id' => 'sr','name' => 'Adtexo','description' => NULL,'slug' => 'adtexo','path' => 'Adtexo','created_at' => '2019-12-13 13:14:06','updated_at' => '2019-12-13 13:14:06'),
            array('id' => '13','category_id' => '10','lang_id' => 'bs','name' => 'Proizvodi','description' => '','slug' => 'adtexo/proizvodi','path' => 'Adtexo > Proizvodi','created_at' => '2019-12-13 14:22:44','updated_at' => '2019-12-13 14:22:44'),
            array('id' => '14','category_id' => '10','lang_id' => 'rs','name' => 'Proizvodi','description' => '','slug' => 'adtexo/proizvodi','path' => 'Adtexo > Proizvodi','created_at' => '2019-12-13 14:22:53','updated_at' => '2019-12-13 14:22:53'),
            array('id' => '15','category_id' => '10','lang_id' => 'en','name' => 'Products','description' => '','slug' => 'adtexo/products','path' => 'Adtexo > Products','created_at' => '2019-12-13 14:23:05','updated_at' => '2019-12-13 14:23:05'),
            array('id' => '16','category_id' => '11','lang_id' => 'bs','name' => 'Auto','description' => '','slug' => 'adtexo/proizvodi/auto','path' => 'Adtexo > Proizvodi > Auto','created_at' => '2019-12-13 14:23:29','updated_at' => '2019-12-13 14:23:29'),
            array('id' => '17','category_id' => '12','lang_id' => 'bs','name' => 'Građevina','description' => '','slug' => 'adtexo/proizvodi/gradevina','path' => 'Adtexo > Proizvodi > Građevina','created_at' => '2019-12-13 14:23:38','updated_at' => '2019-12-13 14:23:38')
          );
                 
        DB::table('category_translations')->insert($category_translations);        
    }
}
