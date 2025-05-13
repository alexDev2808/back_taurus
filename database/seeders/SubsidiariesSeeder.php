<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class SubsidiariesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('subsidiaries')->insert([
            'id' => Str::uuid(),
            'name' => 'Mexico',
            'slug' => 'mexico',
            'created_at' => now(),  
            'updated_at' => now(),
        ]);
    }
}
