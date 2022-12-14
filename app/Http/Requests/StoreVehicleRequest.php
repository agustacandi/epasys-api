<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreVehicleRequest extends FormRequest
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
            'merek' => 'required|string|max:255',
            'no_polisi' => 'required|string|unique:vehicles|max:10',
            'foto_stnk' => 'required|image:jpeg,jpg,png|max:2048',
            'foto_kendaraan' => 'required|image:jpeg,jpg,png|max:2048',
        ];
    }
}
