<?php

namespace Database\Seeders;

use App\Models\Pet;
use App\Models\User;
use Illuminate\Database\Seeder;
use Tymon\JWTAuth\Facades\JWTAuth;

class PetsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Vaciar la tabla articles.
        Pet::truncate();
        $faker = \Faker\Factory::create();
        // Obtenemos la lista de todos los usuarios creados e
        // iteramos sobre cada uno y simulamos un inicio de
        // sesión con cada uno para crear artículos en su nombre
        $users = User::all(); // Get all users
        foreach ($users as $user) {
            // simular un iniciamossesión con este usuario
            JWTAuth::attempt(['email' => $user->email, 'password' => '12312312']);
            // Y ahora con este usuario creamos algunas mascotas
            $num_pets = 1; // Create 1 items foreach
            for ($j = 0; $j < $num_pets; $j++) {
                Pet::create([
                    'name' => $faker->name,
                    'gender' => $faker->randomElement(['Macho', 'Hembra']),
                    'type' => $faker->randomElement(['Perro', 'Gato', 'Otros']),
                    'size' => $faker->randomElement(['Pequeño', 'Mediano', 'Grande']),
                    'description' => $faker->paragraph,
                    'date_of_birth' => $faker->date(),
                    'image' => $faker->imageUrl(400, 300, null, false)
                ]);
            }
        }
    }
}
