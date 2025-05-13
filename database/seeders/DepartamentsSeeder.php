<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class DepartamentsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $subarea = DB::table('subareas')->where('name', 'Ingenieria')->first();
        DB::table('departaments')->insert([
            'id' => Str::uuid(),
            'name' => 'TI',
            'slug' => 'ti',
            'created_at' => now(),  
            'updated_at' => now(),
            'subarea_id' => $subarea->id
        ]);
    }
}
