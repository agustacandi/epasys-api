<?php

namespace App\Http\Controllers\API;

use App\Helpers\ResponseFormatter;
use App\Http\Controllers\Controller;
use App\Http\Requests\ParkingRequest;
use App\Models\Parking;
use Carbon\Carbon;
use DateTime;
use Exception;
use Illuminate\Http\Request;

class ParkingController extends Controller
{
    public function get(Request $request)
    {
        try {
            $user = $request->user();
            $parkings = Parking::with(['vehicle', 'employee', 'user'])->where('id_user', $user->id)->where('is_expired', true)->latest()->get();
            return ResponseFormatter::success($parkings, 'Berhasil mendapatkan data');
        } catch (Exception $e) {
            return ResponseFormatter::error($e, 'Gagal mendapatkan data', 404);
        }
    }

    public function countCheckIn(Request $request)
    {
        try {
            $parkings = Parking::where('status', 'IN')->where('is_expired', true)->whereDate('created_at', date('Y-m-d'))->count();
            return ResponseFormatter::success($parkings, 'Berhasil mendapatkan data');
        } catch (Exception $e) {
            return ResponseFormatter::error($e, 'Gagal mendapatkan data', 404);
        }
    }

    public function countCheckOut(Request $request)
    {
        try {
            $parkings = Parking::where('is_expired', true)->where('status', 'OUT')->where('is_expired', true)->whereDate('created_at', date('Y-m-d'))->count();
            return ResponseFormatter::success($parkings, 'Berhasil mendapatkan data');
        } catch (Exception $e) {
            return ResponseFormatter::error($e, 'Gagal mendapatkan data', 404);
        }
    }


    public function getLatestHistory(Request $request)
    {
        try {
            $user = $request->user();
            $parkings = Parking::with(['vehicle', 'employee', 'user'])->where('id_user', $user->id)->where('is_expired', true)->latest()->take(2)->get();
            return ResponseFormatter::success($parkings, 'Berhasil mendapatkan data');
        } catch (Exception $e) {
            return ResponseFormatter::error($e, 'Gagal mendapatkan data', 404);
        }
    }

    public function getCheckOut(Request $request)
    {
        try {
            $user = $request->user();
            $parking = Parking::with(['vehicle', 'employee', 'user'])->where('id_user', $user->id)->where('is_expired', false)->where('status', 'OUT')->first();
            return ResponseFormatter::success($parking, 'Berhasil mendapatkan data');
        } catch (Exception $e) {
            return ResponseFormatter::error($e, 'Gagal mendapatkan data', 404);
        }
    }

    public function getCurrentParkings(Request $request)
    {
        try {
            $parkings = Parking::with(['vehicle', 'employee', 'user'])->where('is_expired', true)->whereDate('created_at', date('Y-m-d'))->get();
            return ResponseFormatter::success($parkings, 'Berhasil mendapatkan data');
        } catch (Exception $e) {
            return ResponseFormatter::error($e, 'Gagal mendapatkan data', 404);
        }
    }

    public function store(ParkingRequest $request)
    {
        $request->validated($request->all());

        $parking = Parking::create($request->all());

        return ResponseFormatter::success($parking, 'Successfully add parking data', 201);
    }

    public function confirm(Request $request)
    {
        $id = $request->input(('id'));
        $user = $request->user();
        if ($id) {
            $parking = Parking::with(['user', 'employee', 'vehicle'])->where('nomor_parkir', $id)->first();
            if ($parking) {
                $parking->update([
                    'is_expired' => true,
                    'id_karyawan' => $user->id,
                ]);
                return ResponseFormatter::success($parking, 'Berhasil mengonfirmasi check out parkir');
            } else {
                return ResponseFormatter::error(null, 'Gagal mengonfirmasi check out parkir');
            }
        }
    }
}
