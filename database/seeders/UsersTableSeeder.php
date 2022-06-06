<?php

namespace Database\Seeders;

use App\Models\Admin;
use App\Models\Category;
use App\Models\User;
use App\Models\Writer;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //Code that generate fictitious data and save database
        // Vaciar la tabla.
        User::truncate();
        $faker = \Faker\Factory::create();
        // Crear la misma clave para todos los usuarios
        // conviene hacerlo antes del for para que el seeder
        // no se vuelva lento.
        $password = Hash::make('123123');
        $admin = Admin::create(['credential_number' => '09876547654']);
        // Create first user admin
        $admin->user()->create([
            'name' => 'Administrador',
            'email' => 'admin@prueba.com',
            'password' => $password,
            'role' => 'ROLE_ADMIN'
        ]);
        // Generar algunos usuarios para nuestra aplicacion
        for ($i = 0; $i < 10; $i++) {
            $writer = Writer::create([
                'editorial' => $faker->company,
                'short_bio' => $faker->paragraph
            ]);

            $writer->user()->create([          // Instance
                'name' => $faker->name,
                'email' => $faker->email,
                'password' => $password,
            ]);

            // User subscribe to many categories
            $writer->user->categories()->saveMany(      // Use that instance and assign the prop categories
                $faker->randomElements(   // Array with elements randoms
                    array(
                        // Choice of three randomly
                        Category::find(1),
                        Category::find(2),
                        Category::find(3)
                    ), $faker->numberBetween(1, 3), false
                )
            );
        }
    }
}
