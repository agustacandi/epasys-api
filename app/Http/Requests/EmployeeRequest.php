<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EmployeeRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'nama' => 'required|string|max:255',
            'deskripsi' => 'required|string|max:255',
            'email' => 'required|unique:employees|email:rfc,dns|string|max:255',
            'role' => 'required|string',
            'alamat' => 'required|string',
            'avatar' => 'required|image:png,jpg,jpeg|max:2048',
            'no_telepon' => 'required|unique:employees|max:20',
            'password' => 'required|min:8|confirmed|string',
        ];
    }
}
