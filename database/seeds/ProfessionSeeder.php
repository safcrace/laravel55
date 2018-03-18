<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProfessionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // DB::insert('INSERT INTO professions (name) VALUES (:name)', ['name' => 'Desarrollador Back-end']);
        DB::table('professions')->insert([
            'name' => 'Desarrollador Back-end',
        ]);

        DB::table('professions')->insert([
            'name' => 'Desarrollador Front-end',
        ]);

        DB::table('professions')->insert([
            'name' => 'Diseñador Web',
        ]);
    }
}
