<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class AreaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $subsidiary = DB::table('subsidiaries')->where('name', 'Mexico')->first();
        DB::table('areas')->insert([
            'id' => Str::uuid(),
            'name' => 'Manufactura Bancor',
            'slug' => 'manufactura-bancor',
            'created_at' => now(),  
            'updated_at' => now(),
            'subsidiary_id' => $subsidiary->id
        ]);
    }
}
