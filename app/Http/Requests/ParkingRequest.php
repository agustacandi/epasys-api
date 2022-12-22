<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ParkingRequest extends FormRequest
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
            'nomor_parkir' => 'required|string|unique:parkings|max:30',
            'status' => 'required|string',
            'helm' => 'required|integer',
            'is_expired' => 'boolean',
            'id_kendaraan' => 'required|integer',
            'id_karyawan' => 'integer',
        ];
    }
}
