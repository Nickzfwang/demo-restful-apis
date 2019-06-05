<?php

use Illuminate\Database\Seeder;
use App\Entities\Post;

class PostsTableSeeder extends Seeder
{
    /**
     * 執行測試 RESTful APIs Post Tabel
     *
     * @return void
     */
    public function run()
    {
        Post::truncate();

        $faker = \Faker\Factory::create();

        for ($i = 0; $i < 5; $i++) {
            Post::create([
                'user_id' => 'system_test',
                'content' => $faker->paragraph,
            ]);
        }
    }
}
