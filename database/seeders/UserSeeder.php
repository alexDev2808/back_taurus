<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $departament = DB::table('departaments')->where('name', 'TI')->first();
        DB::table('users')->insert([
            'id' => Str::uuid(),
            'name' => 'J Alexis',
            'app' => 'Tenorio',
            'apm' => 'Hernandez',
            'email' => 'jtenorio@taurus.com.mx',
            'password' => bcrypt('Jaxx.2808.'),
            'id_empleado' => '0448',
            'rol' => 'Admin',
            'isActive' => 1,
            'departament_id' => $departament->id,
            'created_at' => now(),  
            'updated_at' => now(),
        ]);
    }
}
