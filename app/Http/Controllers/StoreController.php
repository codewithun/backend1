<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Store;
use Illuminate\Support\Facades\Storage;

class StoreController extends Controller
{
    public function index()
    {
        try {
            $stores = Store::all();
            return response()->json([
                'success' => true,
                'data' => $stores
            ]);
        } catch (\Exception $e) {
            Log::error('Error fetching stores:', ['error' => $e->getMessage()]);
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch stores'
            ], 500);
        }
    }

    public function store(Request $request)
    {
        try {
            Log::info('Request received:', $request->all());

            $validated = $request->validate([
                'nama_usaha' => 'required|string|max:255',
                'jenis_usaha' => 'required|string|max:255',
                'alamat' => 'required|string|max:255',
                'gambar' => 'nullable|image|mimes:jpeg,png,jpg,gif',
                'user_id' => 'nullable|exists:users,id'
            ]);

            $userId = $validated['user_id'] ?? Auth::id();

            if (!$userId) {
                return response()->json([
                    'success' => false,
                    'message' => 'User ID is required and must be authenticated.'
                ], 401);
            }

            // Cek apakah store sudah ada untuk user_id yang diberikan
            $storeData = DB::table('stores')->where('user_id', $userId)->first();
            if ($storeData) {
                // Jika gambar kosong, set menjadi string kosong
                $storeData->gambar = $storeData->gambar ?? '';
            }

            $gambar = null;
            if ($request->hasFile('gambar')) {
                $gambar = $request->file('gambar')->store('gambar');
            }

            $store = Store::create([
                'nama_usaha' => $validated['nama_usaha'],
                'jenis_usaha' => $validated['jenis_usaha'],
                'alamat' => $validated['alamat'],
                'gambar' => $gambar,
                'user_id' => $userId
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Store created successfully',
                'data' => $store
            ], 201);
        } catch (\Exception $e) {
            Log::error('Error creating store:', ['error' => $e->getMessage()]);
            return response()->json([
                'success' => false,
                'message' => 'Failed to create store'
            ], 500);
        }
    }

    public function show($id)
    {
        try {
            $store = Store::findOrFail($id);
            return response()->json([
                'success' => true,
                'data' => $store
            ]);
        } catch (\Exception $e) {
            Log::error('Error fetching store:', ['error' => $e->getMessage()]);
            return response()->json([
                'success' => false,
                'message' => 'Store not found'
            ], 404);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $store = Store::findOrFail($id);

            // Validate input
            $validated = $request->validate([
                'nama_usaha' => 'required|string|max:255',
                'jenis_usaha' => 'required|string|max:255',
                'alamat' => 'required|string',
                'gambar' => 'nullable|image|mimes:jpeg,png,jpg,gif',
                'user_id' => 'required|exists:users,id'
            ]);

            // Check if user_id matches store's user_id
            if ($validated['user_id'] != $store->user_id) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized'
                ], 403);
            }

            // Update data
            $store->nama_usaha = $validated['nama_usaha'];
            $store->jenis_usaha = $validated['jenis_usaha'];
            $store->alamat = $validated['alamat'];

            // Handle file upload
            if ($request->hasFile('gambar')) {
                // Delete old image if exists
                if ($store->gambar) {
                    Storage::delete($store->gambar);
                }
                $store->gambar = $request->file('gambar')->store('stores');
            }

            $store->save();

            return response()->json([
                'success' => true,
                'message' => 'Store updated successfully',
                'data' => $store
            ]);
        } catch (\Exception $e) {
            Log::error('Store update error:', ['error' => $e->getMessage()]);
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }
    public function destroy($id)
    {
        try {
            $store = Store::findOrFail($id);

            // Hapus gambar jika ada
            if ($store->gambar) {
                Storage::delete($store->gambar);
            }

            $store->delete();

            return response()->json([
                'success' => true,
                'message' => 'Store deleted successfully'
            ]);
        } catch (\Exception $e) {
            Log::error('Error deleting store:', ['error' => $e->getMessage()]);
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete store'
            ], 500);
        }
    }
}
