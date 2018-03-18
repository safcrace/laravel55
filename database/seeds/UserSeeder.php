<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //$profession = DB::select('SELECT id FROM professions WHERE name = ? ', ['Desarrollador Back-end']);

        // $profession = DB::table('professions')->where('name', '=', 'Desarrollador Back-end')->value('id');
        //dd($profession);

        DB::table('users')->insert([
            'name' => 'Sender Flores',
            'email' => 'safcrace@gmail.com',
            'password' => bcrypt('laravel'),
            'profession_id' => DB::table('professions')->whereName('Desarrollador Back-end')->value('id')
        ]);
    }
}
