<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UserTest extends TestCase
{
	public function testUserInfoTokenNull()
	{
		$this->json('GET', '/api/member/info')
            ->assertStatus(401)
            ->assertJsonStructure([
                'result',
                'message'
            ]);
	}

	public function testUserInfoTokenError()
	{
		factory(\App\Entities\User::class)->create([
            'username' => 'test',
            'name' => 'test',
            'email' => 'test@gmail.com',
            'password' => bcrypt('123456'),
        ]);

        $credentials = ['username' => 'test', 'password' => '123456'];

        $token = auth('api')->attempt($credentials);

        auth('api')->logout();

        $headers = ['Authorization' => 'Bearer ' . $token];

        $this->json('GET', '/api/member/info', [], $headers)
            ->assertStatus(401)
            ->assertJson([
            	'result' => 1,
                'message' => 'The token has been blacklisted'
            ]);
	}

    public function testLoginUserInfo()
    {
    	factory(\App\Entities\User::class)->create([
            'username' => 'test',
            'name' => 'test',
            'email' => 'test@gmail.com',
            'password' => bcrypt('123456'),
        ]);

        $credentials = ['username' => 'test', 'password' => '123456'];

        $token = auth('api')->attempt($credentials);

        $headers = ['Authorization' => 'Bearer ' . $token];

        $this->json('GET', '/api/member/info', [], $headers)
            ->assertStatus(200)
            ->assertJsonStructure([
                'result',
                'message' => [
                	'username',
                	'name',
                	'email',
                	'registerTime'
                ]
            ]);
    }
}
