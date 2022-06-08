<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategoriesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        // Vaciamos la tabla categories
        Category::truncate();
        $faker = \Faker\Factory::create();
        for ($i = 0; $i < 3; $i++) {
            Category::create([
                'type' => $faker->randomElement(
                    array(
                        'dogs',
                        'cats',
                        'others'
                    )
                )
            ]);
        }
    }
}
