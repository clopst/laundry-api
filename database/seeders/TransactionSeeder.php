<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TransactionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $transactions = [
            [
                'cashier_id' => 3,
                'outlet_id' => 1,
                'customer_id' => 1,
                'invoice' => 'LDB0001',
                'date' => '2021-03-01',
                'product_id' => 1,
                'qty' => 4,
                'total_price' => 28000,
                'status' => 'done',
                'payment' => 'done'

            ],
            [
                'cashier_id' => 4,
                'outlet_id' => 2,
                'customer_id' => 2,
                'invoice' => 'LDB0002',
                'date' => '2021-03-01',
                'product_id' => 2,
                'qty' => 1,
                'total_price' => 35000,
                'status' => 'process',
                'payment' => 'pending'
            ]
        ];

        DB::table('transactions')->insert($transactions);
    }
}
