<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

/**
 * Class DatabaseSeeder
 */
class DatabaseSeeder extends Seeder
{
    /**
     * Tables that are not for truncate.
     *
     * @var array
     */
    private $noTruncateTables = ['activities', 'migrations', 'revisions'];
    
    /**
     * Call seeders.
     *
     * @return void
     */
    private function callSeeders()
    {
        $this->call(CodeBooksTableSeeder::class);
        $this->call(UsersTableSeeder::class);
        $this->call(RolesTableSeeder::class);
        $this->call(PermissionsTableSeeder::class);
        $this->call(BrandsTableSeeder::class);
        $this->call(CategoriesTableSeeder::class);
        $this->call(StocksTableSeeder::class);
        $this->call(ProductsTableSeeder::class);
        $this->call(CitiesTableSeeder::class);
    }
    
    
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Model: Unguard
        Model::unguard();
    
        // Database: Foreign key
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
    
        // Truncate tables
        $this->truncateTables();
    
        // Seeders
        $this->callSeeders();
    
        // Database: Foreign key
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    
        // Model: Reguard
        Model::reguard();
    }
    
    /**
     * Truncate tables.
     *
     * @return void
     */
    private function truncateTables()
    {
        // Tables in database.name
        $tables_in = 'Tables_in_'.config('database.connections.'.config('database.default').'.database');
        
        // Tables: Show
        $tables = DB::select('SHOW TABLES');
        
        // Foreach
        foreach ($tables as $table) {
            if (!in_array($table->$tables_in, $this->noTruncateTables)) {
                DB::table($table->$tables_in)->truncate();
            }
        }
    }
}
