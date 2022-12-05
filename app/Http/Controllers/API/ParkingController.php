<?php

namespace App\Http\Controllers\API;

use App\Helpers\ResponseFormatter;
use App\Http\Controllers\Controller;
use App\Http\Requests\ParkingRequest;
use App\Models\Parking;
use GuzzleHttp\Psr7\Response;
use Illuminate\Http\Request;

class ParkingController extends Controller
{
    public function all(Request $request)
    {
        $parking = Parking::with(['vechile', 'employee', 'user'])->get();
        return ResponseFormatter::success(
            $parking,
            'Successfully get all data vechiles'
        );
    }

    public function store(ParkingRequest $request)
    {
        $request->validated($request->all());

        $parking = Parking::create([
            'status' => $request->status,
            'helm' => $request->helm,
            'is_expired' => $request->is_expired,
            'id_kendaraan' => $request->id_kendaraan,
            'id_karyawan' => $request->id_karyawan,
            'id_user' => $request->id_user,
        ]);

        return ResponseFormatter::success($parking, 'Successfully add parking data', 201);
    }

    public function confirm(Request $request)
    {
        $id = $request->input(('id'));
        if ($id) {
            $parking = Parking::where('id', $id)->first();
            if ($parking) {
                $parking->update([
                    'is_expired' => true
                ]);
                return ResponseFormatter::success($parking, 'Berhasil mengonfirmasi check out parkir');
            } else {
                return ResponseFormatter::error(null, 'Gagal mengonfirmasi check out parkir');
            }
        }
    }
}
