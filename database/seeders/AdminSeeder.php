<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            'first_name' => 'super',
            'last_name' => '--',
            'email' => 'super@gmail.com',
            'phone' => '0000000000',
            'password' => bcrypt('123456789'),
            'role' => 'employee',
        ]);

        DB::table('users')->insert([
            'first_name' => 'admin',
            'last_name' => '--',
            'email' => 'admin@gmail.com',
            'phone' => '1111111111',
            'password' => bcrypt('123456789'),
            'role' => 'employee',
        ]);
    }
}
