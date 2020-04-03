<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run()
    {
        DB::table('users')->truncate();
        DB::table('users')->insert(
            [
                [
                    'name' => "Bob",
                    'email' => 'bob@sw.com',
                    'password' => Hash::make('bobpassword'),
                ],
                [
                    'name' => "Joe",
                    'email' => 'joe@sw.com',
                    'password' => Hash::make('joepassword'),
                ]
            ]);
    }
}
