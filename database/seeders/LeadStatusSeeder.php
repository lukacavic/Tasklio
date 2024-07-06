<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LeadStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('lead_statuses')->insert([
            'name' => 'Klijent',
            'project_id' => 1,
            'organisation_id' => 1,
            'is_client' => true,
            'color' => '#50cf0b',
            'sort_order' => 999,
        ]);
    }
}
