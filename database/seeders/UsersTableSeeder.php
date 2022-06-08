<?php

namespace Database\Seeders;

use App\Models\Admin;
use App\Models\Category;
use App\Models\Protector;
use App\Models\User;
use App\Models\Adopter;
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
        // Empty table.
        User::truncate();
        $faker = \Faker\Factory::create();
        // Create the same password for all users
        $password = Hash::make('12312312');
        $admin = Admin::create(['identity_card' => '1754753668']);
        // Create first user admin
        $admin->user()->create([
            'name' => 'Admin',
            'last_name' => 'Main',
            'cellphone' => '0988020103',
            'address' => 'Fcs. de Quezada y Diego de Palomino',
            'image' => $faker->imageUrl(600, 500, null, false),
            'date_of_birth' => $faker->date($format = 'd-m-Y', $max = 'now'),
            'email' => 'admin@prueba.com',
            'password' => $password,
            'role' => 'ROLE_ADMIN'
        ]);
        // Generate some users for app
        for ($i = 0; $i < 10; $i++) {
            $protector = Protector::create([
                'company' => $faker->company,
                'short_bio' => $faker->paragraph
            ]);

            $protector->user()->create([          // Instance
                'name' => $faker->firstName,
                'last_name' => $faker->lastName,
                'cellphone' => $faker->phoneNumber,
                'address' => $faker->address,
                'image' => $faker->imageUrl(400, 300, null, false),
                'date_of_birth' => $faker->date('d-m-Y', 'now'),
                'email' => $faker->email,
                'password' => $password,
                'role' => 'ROLE_PROTECTOR'
            ]);

            $adopter = Adopter::create([
                'company' => $faker->company,
                'short_bio' => $faker->paragraph
            ]);

            $adopter->user()->create([          // Instance
                'name' => $faker->firstName,
                'last_name' => $faker->lastName,
                'cellphone' => $faker->phoneNumber,
                'address' => $faker->address,
                'image' => $faker->imageUrl(400, 300, null, false),
                'date_of_birth' => $faker->date('d-m-Y', 'now'),
                'email' => $faker->email,
                'password' => $password,
                'role' => 'ROLE_ADOPTER'
            ]);

            // User subscribe to many categories
            $adopter->user->categories()->saveMany(      // Use that instance and assign the prop categories
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
