<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PaymentMethodManagementSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \DB::table('payment_types')->insert([
            ["payment_type" => 'cash', 'status' => 'available'],
            ["payment_type" => 'bank_transfer', 'status' => 'available'],
            ["payment_type" => 'e_wallet', 'status' => 'not available'],
        ]);

        \DB::table('banks')->insert([
            ["payment_type_id" => 2, "name" => 'bca', 'status' => 'available'],
            ["payment_type_id" => 2, "name" => 'bri', 'status' => 'available']
        ]);
    }
}
