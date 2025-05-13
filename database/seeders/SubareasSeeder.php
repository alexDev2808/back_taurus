<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class SubareasSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $area = DB::table('areas')->where('slug', 'manufactura-bancor')->first();
        DB::table('subareas')->insert([
            'id' => Str::uuid(),
            'name' => 'Ingenieria',
            'slug' => 'ingenieria',
            'created_at' => now(),  
            'updated_at' => now(),
            'area_id' => $area->id
        ]);
    }
}
