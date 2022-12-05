<?php

namespace App\Http\Controllers\API;

use App\Helpers\ResponseFormatter;
use App\Http\Controllers\Controller;
use App\Http\Requests\EmployeeRequest;
use App\Models\Employee;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class EmployeeController extends Controller
{
    public function store(EmployeeRequest $request)
    {
        try {
            $request->validated($request->all());
            $avatar = null;
            if ($request->file('avatar')) {
                $avatar = $request->avatar->store('assets/karyawan', 'public');
            }


            Employee::create([
                'nama' => $request->nama,
                'email' => $request->email,
                'role' => $request->role,
                'alamat' => $request->alamat,
                'avatar' => $avatar,
                'no_telepon' => $request->no_telepon,
                'password' => Hash::make($request->password),
            ]);

            $employee = Employee::where('email', $request->email)->first();


            return ResponseFormatter::success([
                'employee' => $employee,
            ], 'Successfull add employee', 201);
        } catch (Exception $error) {
            return ResponseFormatter::error([
                'error' => $error
            ], 'Failed to add employee', 500);
        }
    }

    public function login(Request $request)
    {
        try {
            $request->validate([
                'email' => 'required|email:dns,rfc|string|max:255',
                'password' => 'required|string|min:8',
            ]);

            $credentials = request(['email', 'password']);

            if (!Auth::attempt($credentials)) {
                return ResponseFormatter::error([
                    'message' => 'Unauthorized',
                ], 'Authentication failed', 500);
            }

            $employee = Employee::where('email', $request->email)->first();

            if (!Hash::check($request->password, $employee->password)) {
                return ResponseFormatter::error([
                    'message' => 'Unauthorized',
                ], 'Invalid credentials', 500);
            }

            $tokenResult = $employee->createToken('authToken')->plainTextToken;

            return ResponseFormatter::success([
                'token' => $tokenResult,
                'token_type' => 'Bearer',
                'employee' => $employee
            ], 'Authenticated');
        } catch (Exception $error) {
            return ResponseFormatter::error([
                'message' => 'Something went wrong',
                'error' => $error
            ], 'Authentication failed', 500);
        }
    }

    public function register(Request $request)
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
}
