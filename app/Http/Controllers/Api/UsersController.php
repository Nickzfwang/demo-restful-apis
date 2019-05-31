<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Service\Api\UsersService;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Facades\JWTAuth;

class UsersController extends Controller
{
	protected $userService = null;

    public function __construct(UsersService $userService)
    {
        $this->userService = $userService;
    }
	// 會員註冊
    public function postRegister(Request $request)
    {
    	$validator = Validator::make($request->all(), [
            'username' => 'required|string|unique:users|regex:/(^[A-Za-z0-9 ]+$)+/|max:30',
            'password' => 'required|string|min:6|confirmed',
            'email' => 'required|string|email|max:255|unique:users',
            'name' => 'required|string|max:10'
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
        $this->userService->postRegisterData($request->all());
        return response()->json([
        	'result' => 0,
        	'message' => '註冊成功'
        ], 200);
    }
    // 會員登入
    public function postLogin(Request $request)
    {
        $credentials = $request->only('username', 'password');
        if (!$token = JWTAuth::attempt($credentials)) {
            return response()->json([
                'result' => '1',
                'message' => '登入帳號或密碼錯誤',
            ], 401);
        }
        return response()->json([
            'result' => 0,
            'message' => $token
        ], 200);
    }
    // 會員資訊
    public function getMemberInfo()
    {
        $userInfo = $this->userService->memberInfo(JWTAuth::user());
        return response()->json([
            'result' => 0,
            'message' => $userInfo
        ], 200);
    }
    // 個人貼文
    public function getMyPosts()
    {
        $posts = $this->userService->memberPosts(JWTAuth::user());
        return response()->json([
            'result' => 0,
            'message' => $posts
        ], 200);
    }
}
