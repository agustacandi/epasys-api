<?php

namespace App\Http\Controllers\API;

use App\Helpers\ResponseFormatter;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreVechileRequest;
use App\Models\Vechile;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class VechileController extends Controller
{
    public function all(Request $request)
    {
        $id = $request->input('id');
        $limit = $request->input('limit', 6);

        if ($id) {
            $vechile = Vechile::with(['user'])->find($id);

            if ($vechile) {
                return ResponseFormatter::success($vechile, 'Berhasil mendapatkan data');
            } else {
                return ResponseFormatter::error(null, 'Data yang anda cari tidak ada', 404);
            }
        }

        $vechiles = Vechile::with(['user']);

        return ResponseFormatter::success($vechiles->paginate($limit), 'Berhasil mendapatkan data');
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

            Vechile::create([
                'merek' => $request->merek,
                'no_polisi' => $request->no_polisi,
                'foto_stnk' => $foto_stnk,
                'foto_kendaraan' => $foto_kendaraan,
                'id_user' => $request->id_user,
            ]);

            $vechile = Vechile::where('no_polisi', $request->no_polisi)->first();

            $result = Vechile::with(['user'])->find($vechile->id);

            return ResponseFormatter::success([
                'vechile' => $result,
            ], 'Vechile added', 201);
        } catch (Exception $error) {
            return ResponseFormatter::error([
                'error' => $error
            ], 'Failed to add vechile', 500);
        }
    }

    public function update(StoreVechileRequest $request,)
    {
        try {
            $id = $request->input('id');
            $request->validated($request->all());
            $vechile = Vechile::where('id', $id)->first();
            $validatedData = $request->all();
            $foto_stnk = $vechile->foto_stnk;
            $foto_kendaraan = $vechile->foto_kendaraan;
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


            $vechile->update($validatedData);

            return ResponseFormatter::success([
                'vechile' => $vechile,
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
            $vechile = Vechile::with('user')->find($id);
            if ($vechile) {
                File::delete(storage_path('app/public/' . $vechile->foto_stnk));
                File::delete(storage_path('app/public/' . $vechile->foto_kendaraan));
                $vechile->delete();
                return ResponseFormatter::success(null, 'Berhasil menghapus data kendaraan');
            } else {
                return ResponseFormatter::error(null, 'Gagal menghapus data kendaraan');
            }
        }
    }
}
