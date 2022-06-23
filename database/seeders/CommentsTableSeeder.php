<?php

namespace Database\Seeders;

use App\Models\Comment;
use App\Models\Pet;
use App\Models\User;
use Illuminate\Database\Seeder;
use Tymon\JWTAuth\Facades\JWTAuth;

class CommentsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        // Vaciamos la tabla comments
        Comment::truncate();
        $faker = \Faker\Factory::create(); // Instance for faker
        // Obtenemos todos los artículos de la bdd
        $pets = Pet::all();
        // Obtenemos todos los usuarios
        $users = User::all();
        // Iterar
        foreach ($users as $user) {
            // iniciamos sesión con cada uno
            JWTAuth::attempt(['email' => $user->email, 'password' => '12312312']);
            // Creamos un comentario para cada mascota con este usuario
            foreach ($pets as $pet) {
                Comment::create([
                    'text' => $faker->paragraph, // Text of the comment (Paragraph)
                    'pet_id' => $pet->id, // Create the "id" to the owner of the comment, and extract id auto
                ]);
            }
        }
    }
}
