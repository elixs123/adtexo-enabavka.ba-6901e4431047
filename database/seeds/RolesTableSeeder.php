<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

/**
 * Class RolesTableSeeder
 */
class RolesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $roles = array(
            array('id' => '1','name' => 'administrator','label' => 'Administrator','description' => '','status' => 'active','created_at' => '2019-07-05 08:11:39','updated_at' => '2019-07-05 08:11:39'),
            array('id' => '2','name' => 'kupac','label' => 'Kupac','description' => '','status' => 'active','created_at' => '2019-12-12 13:40:03','updated_at' => '2019-12-12 13:40:28'),
            array('id' => '3','name' => 'komercijalista','label' => 'Komercijalista','description' => '','status' => 'active','created_at' => '2019-12-12 13:40:42','updated_at' => '2019-12-12 13:40:42')
        );
        DB::table('roles')->insert($roles);
    
        $role_user = array(
            array('user_id' => '1','role_id' => '1'),
            array('user_id' => '2','role_id' => '1'),
        );
        DB::table('role_user')->insert($role_user);
    }
}
