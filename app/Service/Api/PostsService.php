<?php

namespace App\Service\Api;

use App\Repositories\Api\PostsRepository;

class PostsService
{
    protected $posts = null;

    public function __construct(PostsRepository $posts)
    {
        $this->posts = $posts;
    }

    public function getPostsIndex($user)
    {
    	$posts = $this->posts->postRecords();
        $data = [];
        foreach ($posts as $key => $post) {
            $data[] = [
                'id' => $post->id,
                'userId' => $post->user_id,
                'content' => $post->content,
                'edit' => ($user && $post->user_id == $user->username) ? 1 : 0,
                'create' => date($post->created_at)
            ];
        }
        return $data;
    }

    public function postCreateData($user, $content)
    {
        $this->posts->createPostData($user->username, $content);
    }

    public function postUpdateData($user, $content, $id)
    {
        return $this->posts->updatePostData($user->username, $content, $id);
    }

    public function postDeleteData($user, $id)
    {
        return $this->posts->deletePostData($user->username, $id);
    }
}