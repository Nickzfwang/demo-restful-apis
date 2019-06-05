<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PostTest extends TestCase
{
    /**
     * 測試個人貼文 API
     *
     * @return void
     */
    private function loginUser()
    {
    	$credentials = ['username' => 'system_test', 'password' => '123456'];

        $token = auth('api')->attempt($credentials);

        $headers = ['Authorization' => 'Bearer ' . $token];

        return $headers;
    }

    public function testPostMiddleware()
    {
    	$this->json('GET', '/api/my-posts')
            ->assertStatus(401)
            ->assertJsonStructure([
                'result',
                'message'
            ]);

        $this->json('POST', '/api/posts/create')
            ->assertStatus(401)
            ->assertJsonStructure([
                'result',
                'message'
            ]);

         $this->json('POST', '/api/posts/update/1')
            ->assertStatus(401)
            ->assertJsonStructure([
                'result',
                'message'
            ]);

        $this->json('GET', '/api/posts/delete/1')
            ->assertStatus(401)
            ->assertJsonStructure([
                'result',
                'message'
            ]);

    }

    public function testGetMyPostLists()
    {
        $this->json('GET', '/api/my-posts', [], $this->loginUser())
            ->assertStatus(200)
            ->assertJsonStructure([
                'result',
                'message' => [[
                	'id',
                	'user_id',
                	'content',
                	'created_at',
                	'updated_at'
                ]]
            ]);
    }

    public function testCreatePostRequiresParams()
    {
        $this->json('POST', '/api/posts/create', [], $this->loginUser())
            ->assertStatus(422)
            ->assertJson([
                'result' => 1,
                'message' => '文章內容欄位為必填。'
            ]);
    }

    public function testCreatePostSuccessfully()
    {
        $params = ['content' => 'create data'];

        $this->json('POST', '/api/posts/create', $params, $this->loginUser())
            ->assertStatus(200)
            ->assertJson([
                'result' => 0,
                'message' => '新增文章成功。'
            ]);
    }

    public function testUpdatePostRequiresParams()
    {
        $this->json('POST', '/api/posts/update/1', [], $this->loginUser())
            ->assertStatus(422)
            ->assertJson([
                'result' => 1,
                'message' => '文章內容欄位為必填。'
            ]);
    }

    public function testUpdatePostPermission()
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

        $params = ['content' => 'create data'];

        $this->json('POST', '/api/posts/update/1', $params, $headers)
            ->assertStatus(200)
            ->assertJson([
                'result' => 1,
                'message' => '權限不符，更新失敗。'
            ]);
    }

    public function testUpdatePostSuccessfully()
    {
        $params = ['content' => 'update data'];

        $this->json('POST', '/api/posts/update/1', $params, $this->loginUser())
            ->assertStatus(200)
            ->assertJson([
                'result' => 0,
                'message' => '更新成功。'
            ]);
    }

    public function testDeletePostPermission()
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

        $this->json('GET', '/api/posts/delete/1', [], $headers)
            ->assertStatus(200)
            ->assertJson([
                'result' => 1,
                'message' => '權限不符，刪除失敗。'
            ]);
    }

    public function testDeletePostSuccessfully()
    {
        $this->json('GET', '/api/posts/delete/1', [], $this->loginUser())
            ->assertStatus(200)
            ->assertJson([
                'result' => 0,
                'message' => '刪除成功。'
            ]);
    }
}
