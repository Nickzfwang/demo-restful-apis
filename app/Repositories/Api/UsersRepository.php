<?php

namespace App\Repositories\Api;

use Illuminate\Support\Facades\Hash;
use App\Entities\User;

class UsersRepository
{
	public function createRegisterData($params)
	{
		User::create([
			'username' => $params['username'],
			'password' => Hash::make($params['password']),
			'email' => $params['email'],
			'name' => $params['name']
		]);
	}
}