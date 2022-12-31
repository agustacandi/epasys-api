<?php

namespace App\Http\Controllers\API;

use App\Helpers\ResponseFormatter;
use App\Http\Controllers\Controller;
use App\Http\Requests\BroadcastRequest;
use App\Models\Broadcast;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class BroadcastController extends Controller
{
    public function all(Request $request)
    {
        $id = $request->input('id');

        if ($id) {
            $broadcast = Broadcast::with(['employee'])->find($id);

            if ($broadcast) {
                return ResponseFormatter::success($broadcast, 'Berhasil mendapatkan data');
            } else {
                return ResponseFormatter::error(null, 'Data yang anda cari tidak ada', 404);
            }
        }

        $broadcasts = Broadcast::with(['employee'])->get();

        return ResponseFormatter::success($broadcasts, 'Berhasil mendapatkan data');
    }

    public function store(BroadcastRequest $request)
    {
        try {
            $request->validated($request->all());
            $user = $request->user();
            $img_url = null;
            if ($request->file('img_url')) {
                $img_url = $request->img_url->store('assets/broadcast', 'public');
            }

            $broadcast = Broadcast::create([
                'judul' => $request->judul,
                'body' => $request->body,
                'img_url' => $img_url,
                'id_karyawan' => $user->id,
            ]);

            $result = Broadcast::with(['employee'])->find($broadcast->id);

            return ResponseFormatter::success([
                'broadcast' => $result,
            ], 'Broadcast added', 201);
        } catch (Exception $error) {
            return ResponseFormatter::error([
                'error' => $error
            ], 'Failed to add broadcast', 500);
        }
    }

    public function update(Request $request,)
    {
        try {
            $id = $request->input('id');
            $request->validate(
                [
                    'judul' => 'string|max:255',
                    'body' => 'string',
                    'img_url' => 'image:png,jpg,jpeg|max:2048',
                ]
            );
            $broadcast = Broadcast::where('id', $id)->first();
            $validatedData = $request->all();
            $img_url = $broadcast->img_url;
            if ($request->file('img_url')) {
                File::delete(storage_path('app/public/' . $img_url));
                $img_url = $request->img_url->store('assets/broadcast', 'public');
            }

            $validatedData['judul'] = $request->judul;
            $validatedData['body'] = $request->body;
            $validatedData['img_url'] = $img_url;


            $broadcast->update($validatedData);

            return ResponseFormatter::success([
                'broadcast' => $broadcast,
            ], 'Berhasil mengubah data broadcast', 200);
        } catch (Exception $error) {
            return ResponseFormatter::error([
                'error' => $error
            ], 'Gagal mengubah data broadcast', 500);
        }
    }

    public function getBroadcastsByToken(Request $request)
    {
        try {
            $user = $request->user();
            $broadcasts = Broadcast::with(['employee'])->where('id_karyawan', $user->id)->get();
            if ($broadcasts) {
                return ResponseFormatter::success($broadcasts, 'Berhasil mendapatkan data');
            } else {
                return ResponseFormatter::error(null, 'Data yang anda cari tidak ada', 404);
            }
        } catch (Exception $error) {
            return ResponseFormatter::error([
                'error' => $error
            ], 'Gagal mendapatkan data broadcast', 500);
        }
    }

    public function delete(Request $request)
    {
        $id = $request->input('id');

        if ($id) {
            $broadcast = Broadcast::with('employee')->find($id);
            if ($broadcast) {
                File::delete(storage_path('app/public/' . $broadcast->img_url));
                $broadcast->delete();
                return ResponseFormatter::success(null, 'Success deleted broadcast');
            } else {
                return ResponseFormatter::error(null, 'Failed to delete broadcast');
            }
        } else {
            return ResponseFormatter::error(null, 'Failed to delete broadcast');
        }
    }
}
