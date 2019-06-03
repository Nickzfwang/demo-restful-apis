<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class RegisterTest extends TestCase
{
	private function initParams()
	{
		return [
			'username' => 'test',
        	'name' => 'test',
        	'email' => 'test@gmail.com',
        	'password' => '123456',
        	'password_confirmation' => '123456'
		];
	}

    public function testRegisterRequiresParams()
    {
        $this->json('POST', '/api/register')
            ->assertStatus(422)
            ->assertJson([
            	'result' => 1,
                'message' => '帳號欄位為必填。密碼欄位為必填。電子信箱欄位為必填。姓名欄位為必填。'
            ]);
    }

    public function testRegisterAlreadyBeenTaken()
    {
    	factory(\App\Entities\User::class)->create([
            'username' => 'test',
            'email' => 'test@gmail.com',
            'password' => bcrypt('123456'),
        ]);

        $this->json('POST', '/api/register', $this->initParams())
            ->assertStatus(422)
            ->assertJson([
            	'result' => 1,
                'message' => '帳號欄位內容已存在。電子信箱欄位內容已存在。'
            ]);
    }

    public function testRegisterConfirmation()
    {
    	$params = [
    		'username' => 'test',
        	'name' => 'test',
        	'email' => 'test@gmail.com',
        	'password' => '123456'
    	];

    	$this->json('POST', '/api/register', $params)
            ->assertStatus(422)
            ->assertJson([
            	'result' => 1,
                'message' => '確認密碼輸入不一致。'
            ]);
    }

    public function testRegisterSuccessfully()
    {
        $this->json('POST', 'api/register', $this->initParams())
            ->assertStatus(200)
            ->assertJson([
            	'result' => 0,
                'message' => '註冊成功。'
            ]);
    }
}
