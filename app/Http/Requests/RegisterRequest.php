<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
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
            'email' => 'required|unique:users|email:rfc,dns|string|max:100',
            'alamat' => 'required|string',
            'nim' => 'required|max:9|string|unique:users',
            'no_telepon' => 'required|max:20|string|unique:users',
            'tanggal_lahir' => 'required',
            'password' => 'required|min:8|confirmed|string',
        ];
    }
}
