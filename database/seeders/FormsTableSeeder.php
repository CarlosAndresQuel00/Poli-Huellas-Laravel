<?php

namespace Database\Seeders;

use App\Models\Form;
use App\Models\Pet;
use App\Models\User;
use Illuminate\Database\Seeder;
use Tymon\JWTAuth\Facades\JWTAuth;

class FormsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Vaciar la tabla articles.
        Form::truncate();
        $faker = \Faker\Factory::create();
        $pets = Pet::all();
        // Obtenemos la lista de todos los usuarios creados e
        // iteramos sobre cada uno y simulamos un inicio de
        // sesión con cada uno para crear artículos en su nombre
        $users = User::all(); // Get all users
        foreach ($users as $user) {
            // simular un iniciamossesión con este usuario
            JWTAuth::attempt(['email' => $user->email, 'password' => '12312312']);
            // Y ahora con este usuario creamos algunos formularios
            foreach ($pets as $pet) {
                Form::create([
                    'responsible' => $faker->name,
                    'reason' => $faker->paragraph,
                    'home' => $faker->word,
                    'description' => $faker->paragraph,
                    'diseases' => $faker->boolean,
                    'children' => $faker->boolean,
                    'time' => $faker->boolean,
                    'trip' => $faker->word,
                    'new' => $faker->boolean,
                    'animals' => $faker->boolean,
                    'pet_id' => $pet->id,
                    'category_id' => $faker->numberBetween(1, 3), // Set the category to the that belong that article
                ]);
            }
        }
    }
}
