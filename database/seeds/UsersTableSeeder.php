<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

/**
 * Class UsersTableSeeder
 */
class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $users = array(
            array('id' => '1','email' => 'emir.agic@lampa.ba','password' => '$2y$10$I7CPsg3souUKAUaN\/ndOheQTU\/iSau4WA9rSSoKCSh6nL\/sOvj88m','status' => 'active','photo' => null,'remember_token' => 'jQxU2H9n0bgytmJZxujE30jdRyDqnJ8AHWWfviHfPfFf4jPTXeDKjhKmSLtE','created_at' => '2019-12-04 15:49:25','updated_at' => '2019-12-04 15:49:25'),
            array('id' => '2','email' => 'muamer.hodzic@gmail.com','password' => '$2a$12$9zgA./yV1h/h7yONAJr8Ku.8T4lNEizKuMRp7uoi7ic17Ql4pK6ZK','status' => 'active','photo' => null,'remember_token' => 'e1a4q01ugK18PohFtM6nY6cIE7RDbLdQZz46335PgfUCnJc0Nq2Ako7vTfZL','created_at' => '2019-12-04 15:49:45','updated_at' => '2019-12-04 15:49:45'),
            array('id' => '999','email' => 'faruk@appit.ba','password' => '$2y$10$qXPJdxt5rIxu3dH8sRQgmecAlCP7t04sPL5KjCDlNYGRUrStTn6Q.','status' => 'active','photo' => null,'remember_token' => null,'created_at' => '2021-07-04 07:35:00','updated_at' => '2021-07-04 07:35:00')


        );

        DB::table('users')->insert($users);
    }
}
