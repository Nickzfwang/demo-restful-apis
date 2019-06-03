<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Entities\User;

class UsersTableSeeder extends Seeder
{
    /**
     * 執行測試 RESTful APIs User Tabel
     *
     * @return void
     */
    public function run()
    {
        User::truncate();

        $faker = \Faker\Factory::create();

        $password = Hash::make('123456');

        for ($i = 0; $i < 10; $i++) {
            User::create([
            	'username' => $faker->username,
                'name' => $faker->name,
                'email' => $faker->email,
                'password' => $password,
            ]);
        }
    }
}
