<?php

namespace App\Repositories\Api;

use App\Entities\Post;

class PostsRepository
{
	public function postRecords()
	{
		return Post::orderBy('created_at', 'desc')->get();
	}

	public function createPostData($username, $content)
	{
		Post::create([
			'user_id' => $username,
			'content' => $content
		]);
	}

	public function updatePostData($username, $content, $id)
	{
		$post = Post::where('id', $id)
					->where('user_id', $username)
					->first();
		if ($post) {
			$post->update([
				'content' => $content
			]);
			return true;
		} else {
			return false;
		}
	}

	public function deletePostData($username, $id)
	{
		$post = Post::where('id', $id)
					->where('user_id', $username)
					->first();
		if ($post) {
			$post->delete();
			return true;
		} else {
			return false;
		}
	}
}