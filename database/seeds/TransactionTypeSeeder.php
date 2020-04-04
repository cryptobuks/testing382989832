<?php

use Illuminate\Database\Seeder;

class TransactionTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('transaction_types')->truncate();
        DB::table('transaction_types')->insert(
            [
                [
                    'name' => "withdraw",
                ],
                [
                    'name' => "deposit",
                ],
                [
                    'name' => "transfer",
                ]
            ]);
    }
}
