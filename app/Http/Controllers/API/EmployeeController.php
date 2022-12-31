<?php

namespace App\Http\Controllers\API;

use App\Helpers\ResponseFormatter;
use App\Http\Controllers\Controller;
use App\Http\Requests\EmployeeLoginRequest;
use App\Http\Requests\EmployeeRegisterRequest;
use App\Http\Requests\EmployeeRequest;
use App\Models\Employee;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator as FacadesValidator;
use Nette\Utils\Strings;

class EmployeeController extends Controller
{
    public function all(Request $request)
    {
        try {
            $role = $request->input('role');
            if ($role) {
                $roleUppercase = Strings::upper($role);
                $employee = Employee::where('role', $roleUppercase)->where('is_active', true)->get();
                // $employee = Employee::all();
                return ResponseFormatter::success($employee, 'Berhasil mendapatkan semua data karyawan');
            }
            $employees = Employee::all();
            return ResponseFormatter::success($employees, 'Berhasil mendapatkan semua data karyawan');
        } catch (Exception $e) {
            return ResponseFormatter::error(null, 'Gagal mendapatkan semua data karyawan');
        }
    }

    public function updateAvatar(Request $request)
    {
        $user = $request->user();
        $avatar = $user->avatar;
        $validator = FacadesValidator::make($request->all(), [
            'avatar' => 'required|image|max:2048 '
        ]);

        if ($validator->fails()) {
            return ResponseFormatter::error([
                'error' => $validator->errors(),
            ], 'Update avatar fails', 401);
        }

        if ($request->file('avatar')) {
            if ($avatar) {
                File::delete(storage_path('app/public/' . $avatar));
            }
            $file = $request->avatar->store('assets/karyawan', 'public');

            $user->avatar = $file;

            $user->update();

            return ResponseFormatter::success($user, 'File successfully uploaded', 200);
        }
        return ResponseFormatter::error(null, 'Failed to upload avatar');
    }

    public function activeEmployee(Request $request)
    {
        try {
            $user = $request->user();
            $user->update([
                'is_active' => true
            ]);
            return ResponseFormatter::success($user, 'Berhasil mengaktifkan karyawan');
        } catch (Exception $e) {
            return ResponseFormatter::error(null, 'Gagal mengaktifkan karyawan');
        }
    }

    public function deactiveEmployee(Request $request)
    {
        try {
            $user = $request->user();
            $user->update([
                'is_active' => false
            ]);
            return ResponseFormatter::success($user, 'Berhasil menonaktifkan karyawan');
        } catch (Exception $e) {
            return ResponseFormatter::error(null, 'Gagal menonaktifkan karyawan');
        }
    }

    public function updateProfile(Request $request)
    {
        try {
            $data = $request->all();
            $user = $request->user();
            $user->update($request->all());
            return ResponseFormatter::success($user, 'Profile Updated');
        } catch (Exception $error) {
            return ResponseFormatter::error($error, 'Gagal mengubah data user', 401);
        }
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'password' => 'required|min:8|confirmed|string',
        ]);

        $user = $request->user();

        $user->update([
            'password' => Hash::make($request->password),
        ]);

        return ResponseFormatter::success(null, 'Successfully update password');
    }

    public function getCurrentEmployee(Request $request)
    {
        return ResponseFormatter::success($request->user(), 'Succesfull fetch user');
    }

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
                'deskripsi' => $request->deskripsi,
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

    public function loginEmployee(EmployeeLoginRequest $request)
    {
        try {
            $request->validated($request->all());

            $employee = Employee::where('email', $request->email)->first();

            if ($employee) {
                if (!Hash::check($request->password, $employee->password)) {
                    return ResponseFormatter::error([
                        'message' => 'Unauthorized',
                    ], 'Invalid credentials', 500);
                }
            } else {
                return ResponseFormatter::error([
                    'message' => 'Employee not found',
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

    public function registerEmployee(EmployeeRegisterRequest $request)
    {
        try {
            $request->validated($request->all());
            $role = 'SATPAM';
            Employee::create([
                'nama' => $request->nama,
                'deskripsi' => $request->deskripsi,
                'email' => $request->email,
                'alamat' => $request->alamat,
                'role' => $role,
                'no_telepon' => $request->no_telepon,
                'password' => Hash::make($request->password),
            ]);

            $user = Employee::where('email', $request->email)->first();

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
