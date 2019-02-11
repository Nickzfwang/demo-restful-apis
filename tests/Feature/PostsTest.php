<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PostsTest extends TestCase
{
	public function testPostIndex()
    {
        $response = $this->get('/api/posts/index');

        $response->assertStatus(200);
    }
}
