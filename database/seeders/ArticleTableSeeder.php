<?php

namespace Database\Seeders;

use App\Models\Articl;
use App\Models\User;
use Illuminate\Database\Seeder;
use Tymon\JWTAuth\Facades\JWTAuth;

class ArticleTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        /*
        //Code that generate fictitious data and save database
        // Vaciar la tabla.
        Articl::truncate();
        // Create instance of faker
        $faker = \Faker\Factory::create();

        // Crear 50 artículos ficticios en la tabla
        for ($i = 0; $i < 50; $i++) {
            Articl::create([
                'title' => $faker->sentence,
                'body' => $faker->paragraph,
            ]);
        }
        */
        // Vaciar la tabla articles.
        Articl::truncate();
        $faker = \Faker\Factory::create();
        // Obtenemos la lista de todos los usuarios creados e
        // iteramos sobre cada uno y simulamos un inicio de
        // sesión con cada uno para crear artículos en su nombre
        $users = User::all(); // Get all users
        foreach ($users as $user) {
            // simular un iniciamossesión con este usuario
            JWTAuth::attempt(['email' => $user->email, 'password' => '123123']);
            // Y ahora con este usuario creamos algunos articulos
            $num_articles = 5; // Create 5 items foreach
            for ($j = 0; $j < $num_articles; $j++) {
                Articl::create([
                    'title' => $faker->sentence,
                    'body' => $faker->paragraph,
                    'category_id' => $faker->numberBetween(1, 3), // Set the category to the that belong that article
                    'image' => $faker->imageUrl(400, 300, null, false)
                ]);
            }
        }
    }
}
