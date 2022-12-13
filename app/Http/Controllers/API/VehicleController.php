<?php

namespace App\Http\Controllers\API;

use App\Helpers\ResponseFormatter;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreVechileRequest;
use App\Models\Vehicle;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class VehicleController extends Controller
{
    public function all(Request $request)
    {
        $id = $request->input('id');

        if ($id) {
            $vehicle = Vehicle::with(['user'])->find($id);

            if ($vehicle) {
                return ResponseFormatter::success($vehicle, 'Berhasil mendapatkan data');
            } else {
                return ResponseFormatter::error(null, 'Data yang anda cari tidak ada', 404);
            }
        }

        $vehicles = Vehicle::with(['user'])->get();

        return ResponseFormatter::success($vehicles, 'Berhasil mendapatkan data');
    }

    public function store(StoreVechileRequest $request)
    {
        try {
            $request->validated($request->all());
            $foto_stnk = null;
            $foto_kendaraan = null;
            if ($request->file('foto_stnk')) {
                $foto_stnk = $request->foto_stnk->store('assets/stnk', 'public');
            }

            if ($request->file('foto_kendaraan')) {
                $foto_kendaraan = $request->foto_kendaraan->store('assets/kendaraan', 'public');
            }

            Vehicle::create([
                'merek' => $request->merek,
                'no_polisi' => $request->no_polisi,
                'foto_stnk' => $foto_stnk,
                'foto_kendaraan' => $foto_kendaraan,
                'id_user' => $request->id_user,
            ]);

            $vehicle = Vehicle::where('no_polisi', $request->no_polisi)->first();

            $result = Vehicle::with(['user'])->find($vehicle->id);

            return ResponseFormatter::success([
                'vehicle' => $result,
            ], 'Vehicle added', 201);
        } catch (Exception $error) {
            return ResponseFormatter::error([
                'error' => $error
            ], 'Failed to add vehicle', 500);
        }
    }

    public function update(StoreVechileRequest $request,)
    {
        try {
            $id = $request->input('id');
            $request->validated($request->all());
            $vehicle = Vehicle::where('id', $id)->first();
            $validatedData = $request->all();
            $foto_stnk = $vehicle->foto_stnk;
            $foto_kendaraan = $vehicle->foto_kendaraan;
            if ($request->file('foto_stnk')) {
                File::delete(storage_path('app/public/' . $foto_stnk));
                $foto_stnk = $request->foto_stnk->store('assets/stnk', 'public');
            }

            if ($request->file('foto_kendaraan')) {
                File::delete(storage_path('app/public/' . $foto_kendaraan));
                $foto_kendaraan = $request->foto_kendaraan->store('assets/kendaraan', 'public');
            }

            $validatedData['merek'] = $request->merek;
            $validatedData['no_polisi'] = $request->no_polisi;
            $validatedData['foto_stnk'] = $foto_stnk;
            $validatedData['foto_kendaraan'] = $foto_kendaraan;


            $vehicle->update($validatedData);

            return ResponseFormatter::success([
                'vehicle' => $vehicle,
            ], 'Berhasil mengubah data kendaraan', 200);
        } catch (Exception $error) {
            return ResponseFormatter::error([
                'error' => $error
            ], 'Gagal mengubah data kendaraan', 500);
        }
    }

    public function delete(Request $request)
    {
        $id = $request->input('id');

        if ($id) {
            $vehicle = Vehicle::with('user')->find($id);
            if ($vehicle) {
                File::delete(storage_path('app/public/' . $vehicle->foto_stnk));
                File::delete(storage_path('app/public/' . $vehicle->foto_kendaraan));
                $vehicle->delete();
                return ResponseFormatter::success(null, 'Berhasil menghapus data kendaraan');
            } else {
                return ResponseFormatter::error(null, 'Gagal menghapus data kendaraan');
            }
        }
    }
}
