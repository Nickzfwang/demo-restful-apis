<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Service\Api\PostsService;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\Validator;

class PostsController extends Controller
{
	protected $postsService = null;

    public function __construct(PostsService $postsService)
    {
        $this->postsService = $postsService;
    }
	// 文章列表
    public function postsIndex()
    {
    	$posts = $this->postsService->getPostsIndex(JWTAuth::user());
    	return response()->json([
    		'result' => 0,
    		'message' => $posts
    	], 200);
    }
    // 新增文章
    public function postsCreate(Request $request)
    {
    	$validator = Validator::make($request->all(), [
            'content' => 'required|string|max:255'
        ]);
        if ($validator->fails()) {
        	$info = [];
            foreach ($validator->getMessageBag()->toArray() as $key => $message) {
                $info[] = $message[0];
            }
        	return response()->json([
        		'result' => 1,
        		'message' => implode('', $info)
        	], 422);
        }
        $this->postsService->postCreateData(JWTAuth::user(), $request->content);
        return response()->json([
        	'result' => 0,
        	'message' => '新增文章成功'
        ], 200);
    }
    // 更新文章
    public function postsUpdate(Request $request, $id)
    {
    	$validator = Validator::make($request->all(), [
            'content' => 'required|string|max:255'
        ]);
        if ($validator->fails()) {
        	$info = [];
            foreach ($validator->getMessageBag()->toArray() as $key => $message) {
                $info[] = $message[0];
            }
        	return response()->json([
        		'result' => 1,
        		'message' => implode('', $info)
        	], 422);
        }
        $result = $this->postsService->postUpdateData(JWTAuth::user(), $request->content, $id);
        return response()->json([
        	'result' => $result ? 0 : 1,
        	'message' => $result ? '更新成功' : '權限不符，更新失敗'
        ]);
    }
    // 刪除文章
    public function postsDelete($id)
    {
    	$result = $this->postsService->postDeleteData(JWTAuth::user(), $id);
        return response()->json([
        	'result' => $result ? 0 : 1,
        	'message' => $result ? '刪除成功' : '權限不符，刪除失敗'
        ]);
    }
}
