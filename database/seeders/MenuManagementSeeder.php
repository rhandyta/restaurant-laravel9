<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MenuManagementSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \DB::table('menu_managements')->insert([
            [
                'labelmenu_id' => 1,
                'label_menu' => "Dashboard",
                'role' => "both",
                'path' => "/",
                'icon' => "fa-solid fa-list-check",
                'important' => 1
            ], [
                'labelmenu_id' => 2,
                'label_menu' => "Orders",
                'role' => "both",
                'path' => "/orders",
                'icon' => "fa-solid fa-list-check",
                'important' => 1
            ],
            [
                'labelmenu_id' => 3,
                'label_menu' => "Menu Managements",
                'role' => "manager",
                'path' => "/menu-managements",
                'icon' => "fa-solid fa-list-check",
                'important' => 1
            ],
            [
                'labelmenu_id' => 3,
                'label_menu' => "Tables",
                'role' => "both",
                'path' => "/tables",
                'icon' => "fa-solid fa-list-check",
                'important' => 2
            ],
            [
                'labelmenu_id' => 3,
                'label_menu' => "Food Managements",
                'role' => "both",
                'path' => "/food-managements",
                'icon' => "fa-solid fa-list-check",
                'important' => 3
            ], [
                'labelmenu_id' => 3,
                'label_menu' => "Accounts",
                'role' => "manager",
                'path' => "/accounts",
                'icon' => "fa-solid fa-list-check",
                'important' => 4
            ], 
            [
                'labelmenu_id' => 4,
                'label_menu' => "Sign Out",
                'role' => "both",
                'path' => "/logout",
                'icon' => "fa-solid fa-list-check",
                'important' => 1
            ],
            [
                'labelmenu_id' => 3,
                'label_menu' => "Payment Method",
                'role' => "both",
                'path' => "/payment",
                'icon' => "fa-solid fa-list-check",
                'important' => 5
            ],
        ]);
    }
}
