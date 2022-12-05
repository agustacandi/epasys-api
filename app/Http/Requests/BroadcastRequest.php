<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BroadcastRequest extends FormRequest
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
            'judul' => 'required|string|max:255',
            'body' => 'required|string',
            'img_url' => 'required|image:png,jpg,jpeg|max:2048',
            'id_karyawan' => 'required|integer'
        ];
    }
}
