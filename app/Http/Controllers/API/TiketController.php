<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Tiket;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Log;

class TiketController extends Controller
{
    public function index()
    {
        $tikets = Tiket::all();
        return response()->json($tikets, Response::HTTP_OK);
    }

    public function store(Request $request)
    {
        $request->validate([
            'namaTiket' => 'required|string|max:255',
            'stok' => 'required|integer',
            'hargaJual' => 'required|numeric',
            'keterangan' => 'nullable|string',
            'user_id' => 'required|integer', // Validate user_id
        ]);

        $tiket = Tiket::create([
            'namaTiket' => $request->namaTiket,
            'stok' => $request->stok,
            'hargaJual' => $request->hargaJual,
            'keterangan' => $request->keterangan,
            'user_id' => $request->user_id, // Include user_id
        ]);

        return response()->json($tiket, Response::HTTP_CREATED);
    }

    public function show($id)
    {
        $tiket = Tiket::find($id);

        if (!$tiket) {
            return response()->json(['message' => 'Tiket tidak ditemukan'], Response::HTTP_NOT_FOUND);
        }

        return response()->json($tiket, Response::HTTP_OK);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'namaTiket' => 'required|string|max:255',
            'stok' => 'required|integer',
            'hargaJual' => 'required|numeric',
            'keterangan' => 'nullable|string',
            'user_id' => 'required|integer', // Validate user_id
        ]);

        $tiket = Tiket::find($id);

        if (!$tiket) {
            return response()->json(['message' => 'Tiket tidak ditemukan'], Response::HTTP_NOT_FOUND);
        }

        // Debug log
        Log::info('Updating tiket ID: ' . $id . ' with data: ', $request->all());

        $tiket->update([
            'namaTiket' => $request->namaTiket,
            'stok' => $request->stok,
            'hargaJual' => $request->hargaJual,
            'keterangan' => $request->keterangan,
            'user_id' => $request->user_id, // Include user_id
        ]);

        return response()->json($tiket, Response::HTTP_OK);
    }

    public function destroy($id)
    {
        $tiket = Tiket::find($id);

        if (!$tiket) {
            return response()->json(['message' => 'Tiket tidak ditemukan'], Response::HTTP_NOT_FOUND);
        }

        $tiket->delete();

        return response()->json(['message' => 'Tiket berhasil dihapus'], Response::HTTP_OK);
    }
}
