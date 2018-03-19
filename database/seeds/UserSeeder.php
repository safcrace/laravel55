<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\User;
use App\Models\Profession;

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

        User::create([
            'name' => 'Sender Flores',
            'email' => 'safcrace@gmail.com',
            'password' => bcrypt('laravel'),
            'profession_id' => Profession::whereName('Desarrollador Back-end')->value('id'),
            'is_admin' => true,
        ]);

        factory(User::class)->create([
            'profession_id' => Profession::whereName('Desarrollador Front-end')->value('id')
        ]);

        factory(User::class, 48)->create();
    }
}
