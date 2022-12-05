<?php

namespace App\Http\Controllers\API;

use App\Helpers\ResponseFormatter;
use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator as FacadesValidator;

class UserController extends Controller
{
    public function all(Request $request)
    {
        $id = $request->input('id');
        $limit = $request->input('limit', 6);

        if ($id) {
            $user = User::where('id', $id)->first();

            if ($user) {
                return ResponseFormatter::success($user, 'Berhasil mendapatkan data user');
            } else {
                return ResponseFormatter::error(null, 'Gagal mendapatkan data user', 404);
            }
        }

        $users = User::all();

        return ResponseFormatter::success($users, 'Berhasil mendapatkan data user');
    }
    public function login(LoginRequest $request)
    {
        try {
            $request->validated($request->all());

            $credentials = request(['nim', 'password']);

            if (!Auth::attempt($credentials)) {
                return ResponseFormatter::error([
                    'message' => 'Unauthorized',
                ], 'Authentication failed', 500);
            }

            $user = User::where('nim', $request->nim)->first();

            if (!Hash::check($request->password, $user->password)) {
                return ResponseFormatter::error([
                    'message' => 'Unauthorized',
                ], 'Invalid credentials', 500);
            }

            $tokenResult = $user->createToken('authToken')->plainTextToken;

            return ResponseFormatter::success([
                'token' => $tokenResult,
                'token_type' => 'Bearer',
                'user' => $user
            ], 'Authenticated');
        } catch (Exception $error) {
            return ResponseFormatter::error([
                'message' => 'Something went wrong',
                'error' => $error
            ], 'Authentication failed', 500);
        }
    }

    public function register(RegisterRequest $request)
    {
        try {
            $request->validated($request->all());

            User::create([
                'nama' => $request->nama,
                'email' => $request->email,
                'alamat' => $request->alamat,
                'nim' => $request->nim,
                'password' => Hash::make($request->password),
            ]);

            $user = User::where('email', $request->email)->first();

            $tokenResult = $user->createToken('authToken')->plainTextToken;

            return ResponseFormatter::success([
                'token' => $tokenResult,
                'token_type' => 'Bearer',
                'user' => $user
            ], 'Register was successful', 201);
        } catch (Exception $error) {
            return ResponseFormatter::error([
                'message' => 'Something went wrong',
                'error' => $error
            ], 'Authentication failed', 500);
        }
    }

    public function logout(Request $request)
    {
        try {
            // $token = $request->user()->currentAccessToken()->delete();
            return $request->user();

            // return ResponseFormatter::success([
            //     'success' => $token
            // ], 'Token revoked');
        } catch (Exception $e) {
            ResponseFormatter::error($e, 'Logout failed');
        }
    }

    public function fetch(Request $request)
    {
        return ResponseFormatter::success($request->user(), 'Succesfull fetch user');
    }

    public function updateProfile(Request $request)
    {
        $data = $request->all();
        $id = $request->input('id');

        if ($id) {
            $user = User::where('id', $id)->first();
            $user->update($data);
            return ResponseFormatter::success($user, 'Profile Updated');
        } else {
            return ResponseFormatter::error(null, 'Gagal mengubah data user', 401);
        }
    }

    public function updateAvatar(Request $request)
    {
        $validator = FacadesValidator::make($request->all(), [
            'file' => 'required|image|max:2048 '
        ]);

        if ($validator->fails()) {
            return ResponseFormatter::error([
                'error' => $validator->errors(),
            ], 'Update avatar fails', 401);
        }

        if ($request->file('file')) {
            $file = $request->file->store('assets/user', 'public');

            $user = Auth::user();

            $user->avatar = $file;

            $user->update();

            return ResponseFormatter::success($file, 'File successfully uploaded', 201);
        }
    }
}
