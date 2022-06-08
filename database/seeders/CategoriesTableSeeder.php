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
        Category::create([
            'type' => 'dogs'
        ]);
        Category::create([
            'type' => 'cats'
        ]);
        Category::create([
            'type' => 'others'
        ]);
    }
}
