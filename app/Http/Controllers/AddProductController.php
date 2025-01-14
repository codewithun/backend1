<?php

namespace App\Http\Controllers;

use App\Models\Addproduct;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class AddProductController extends Controller
{
    public function index()
    {
        $products = Addproduct::all();

        // Map untuk menambahkan URL gambar penuh jika ada
        $products->each(function ($product) {
            $product->image = $this->getImageUrl($product->image);
        });

        return response()->json($products);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'namaProduk' => 'required|string|max:255',
            'kodeProduk' => 'required|string|max:255',
            'kategori' => 'required|string',
            'stok' => 'required|integer',
            'hargaJual' => 'required|numeric',
            'keterangan' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        try {
            $data = $request->all();

            // Handle image upload
            if ($request->hasFile('image')) {
                $data['image'] = $this->uploadImage($request->file('image'));
            }

            $product = Addproduct::create($data);
            $product->image = $this->getImageUrl($product->image);

            return response()->json([
                'message' => 'Produk berhasil ditambahkan',
                'data' => $product
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error adding product',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function show($id)
    {
        $product = Addproduct::find($id);

        if (!$product) {
            return response()->json(['message' => 'Produk tidak ditemukan'], 404);
        }

        $product->image = $this->getImageUrl($product->image);
        return response()->json($product);
    }

    public function update(Request $request, $id)
    {
        $product = Addproduct::find($id);

        if (!$product) {
            return response()->json(['message' => 'Produk tidak ditemukan'], 404);
        }

        $validator = Validator::make($request->all(), [
            'namaProduk' => 'sometimes|required|string|max:255',
            'kodeProduk' => 'sometimes|required|string|max:255',
            'kategori' => 'sometimes|required|string',
            'stok' => 'sometimes|required|integer',
            'hargaJual' => 'sometimes|required|numeric',
            'keterangan' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        try {
            $data = $request->except('image');

            if ($request->hasFile('image')) {
                $this->deleteImage($product->image);
                $data['image'] = $this->uploadImage($request->file('image'));
            }

            $product->update($data);
            $product->image = $this->getImageUrl($product->image);

            return response()->json([
                'message' => 'Produk berhasil diupdate',
                'data' => $product
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error updating product',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function destroy($id)
    {
        $product = Addproduct::find($id);

        if (!$product) {
            return response()->json(['message' => 'Produk tidak ditemukan'], 404);
        }

        try {
            $this->deleteImage($product->image);
            $product->delete();

            return response()->json(['message' => 'Produk berhasil dihapus'], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error deleting product',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    private function uploadImage($file)
    {
        // Generate a unique filename with timestamp and original extension
        $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
        // Store the image in the 'products' directory
        $path = $file->storeAs('products', $filename, 'public');
        // Extract and return just the filename (not the full path)
        return $filename;
    }

    private function getImageUrl($filename)
    {
        // Ensure a URL is returned only if a valid filename exists
        return $filename ? url('storage/products/' . $filename) : null;
    }


    private function deleteImage($path)
    {
        if ($path && Storage::disk('public')->exists($path)) {
            Storage::disk('public')->delete($path);
        }
    }
}
