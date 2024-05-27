<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('users')->insert([
            'first_name' => 'User 1',
            'last_name' => 'User 1',
            'name' => 'org1',
            'email' => 'admin@org1.com',
            'administrator' => true,
            'organisation_id' => 1,
            'password' => bcrypt('org1')
        ]);

        DB::table('users')->insert([
            'first_name' => 'User 2',
            'last_name' => 'User 2',
            'name' => 'org2',
            'email' => 'admin@org2.com',
            'administrator' => true,
            'organisation_id' => 2,
            'password' => bcrypt('org2')
        ]);
    }
}
