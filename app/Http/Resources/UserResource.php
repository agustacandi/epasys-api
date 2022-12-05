<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'nama' => $this->nama,
            'nim' => $this->nim,
            'email' => $this->email,
            'alamat' => $this->alamat,
            'role' => $this->role,
            'avatar' => $this->avatar,
            'tanggal_lahir' => $this->tanggal_lahir,
            'no_telepon' => $this->no_telepon,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
