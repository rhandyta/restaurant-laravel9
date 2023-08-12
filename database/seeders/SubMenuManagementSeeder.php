<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SubMenuManagementSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \DB::table('submenu_managements')->insert([
            [
                'menu_id' => 6,
                'role' => 'manager',
                'label_submenu' => 'Manager',
                'path' => '/manager',
                'important' => 3,
            ], [
                'menu_id' => 6,
                'role' => 'manager',
                'label_submenu' => 'Cashier',
                'path' => '/cashier',
                'important' => 2,
            ], [
                'menu_id' => 6,
                'role' => 'both',
                'label_submenu' => 'Customer',
                'path' => '/customer',
                'important' => 1,
            ],
            [
                'menu_id' => 4,
                'role' => 'both',
                'label_submenu' => 'Category Tables',
                'path' => '/categories-tables',
                'important' => 1,
            ], [
                'menu_id' => 4,
                'role' => 'both',
                'label_submenu' => 'Information Tables',
                'path' => '/information-tables',
                'important' => 2,
            ],
            [
                'menu_id' => 5,
                'role' => 'both',
                'label_submenu' => 'Categories Food',
                'path' => '/food-categories',
                'important' => 1,
            ], 
            [
                'menu_id' => 5,
                'role' => 'both',
                'label_submenu' => 'Foods',
                'path' => '/food',
                'important' => 2,
            ],
            [
                'menu_id' => 8,
                'role' => 'both',
                'label_submenu' => 'Payment Types',
                'path' => '/types',
                'important' => 1,
            ],
            [
                'menu_id' => 8,
                'role' => 'both',
                'label_submenu' => 'Banks',
                'path' => '/bank',
                'important' => 2,
            ],

        ]);
    }
}
