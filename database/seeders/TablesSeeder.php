<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TablesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \DB::table('table_categories')->insert([
            ["category" => 'Single Table', 'status' => 'active'],
            ["category" => 'Double Table', 'status' => 'active'],
            ["category" => 'Hangout Table', 'status' => 'active'],
            ["category" => 'Familys Table', 'status' => 'active'],
        ]);
        \DB::table('information_tables')->insert([
            ['category_table_id' => 1, 'seating_capacity' => 2, 'available' => 'available', 'location' => 'Indoor'],
            ['category_table_id' => 1, 'seating_capacity' => 2, 'available' => 'available', 'location' => 'Outdoor'],
            ['category_table_id' => 2, 'seating_capacity' => 4, 'available' => 'available', 'location' => 'Indoor'],
            ['category_table_id' => 2, 'seating_capacity' => 4, 'available' => 'available', 'location' => 'Outdoor'],
            ['category_table_id' => 3, 'seating_capacity' => 6, 'available' => 'available', 'location' => 'Indoor'],
            ['category_table_id' => 3, 'seating_capacity' => 6, 'available' => 'available', 'location' => 'Outdoor'],
            ['category_table_id' => 4, 'seating_capacity' => 8, 'available' => 'available', 'location' => 'Indoor'],
            ['category_table_id' => 4, 'seating_capacity' => 8, 'available' => 'not available', 'location' => 'Outdoor'],
        ]);
    }
}
