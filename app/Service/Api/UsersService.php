<?php

namespace App\Service\Api;

use App\Repositories\Api\UsersRepository;

class UsersService
{
    protected $users = null;

    public function __construct(UsersRepository $users)
    {
        $this->users = $users;
    }

    public function postRegisterData($params)
    {
    	$this->users->createRegisterData($params);
    }

    public function memberInfo($user)
    {
    	return [
    		'username' => $user->username,
    		'name' => $user->name,
    		'email' => $user->email,
    		'registerTime' => date($user->created_at)
    	];
    }

    public function memberPosts($user)
    {
        return $user->postRecords()->orderBy('created_at', 'desc')->get();
    }
}