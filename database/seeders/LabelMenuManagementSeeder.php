<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LabelMenuManagementSeeder extends Seeder
{
    public function run()
    {
        DB::table('labelmenu_managements')->insert(
            [
                [
                    'label_title' => "Main",
                    'role' => 'both',
                    'important' => 1
                ],
                [
                    'label_title' => "Transactions",
                    'role' => 'both',
                    'important' => 2
                ],
                [
                    'label_title' => "Settings",
                    'role' => 'both',
                    'important' => 3
                ],
                [
                    'label_title' => "Logout",
                    'role' => 'both',
                    'important' => 100
                ]
            ]
        );
    }
}
