<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class LoginTest extends TestCase
{
    public function testLoginRequiresParams()
    {
        $this->json('POST', '/api/login')
            ->assertStatus(401)
            ->assertJson([
            	'result' => 1,
                'message' => '登入帳號或密碼錯誤'
            ]);
    }

    public function testLoginSuccessfully()
    {
        factory(\App\Entities\User::class)->create([
            'username' => 'test',
            'password' => bcrypt('123456'),
        ]);

        $params = ['username' => 'test', 'password' => '123456'];

        $this->json('POST', '/api/login', $params)
            ->assertStatus(200)
            ->assertJsonStructure([
                'result',
                'message'
            ]);

    }
}
