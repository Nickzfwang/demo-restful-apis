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

        User::create([
        	'username' => 'system_test',
            'name' => $faker->name,
            'email' => $faker->email,
            'password' => Hash::make('123456'),
        ]);
    }
}
