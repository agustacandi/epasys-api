<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterRequest;
use App\Http\Resources\RegisterResource;
use App\Models\User;
use Illuminate\Http\Request;

class RegisterController extends Controller
{
    public function register(RegisterRequest $request) {
        $user = $request->validated();
        $data = new RegisterResource(User::create([
            'nama' => $user['nama'],
            'nim' => $user['nim'],
            'email' => $user['email'],
            'alamat' => $user['alamat'],
            'password' => bcrypt($user['password']),
            'password_confirmation' => $user['password'],
        ]));
        return $this->sendResponse($data, 'Register Successfully', 201);
    }
}
