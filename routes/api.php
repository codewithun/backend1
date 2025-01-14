<?php

use App\Http\Controllers\API\AuthController;
use Illuminate\Http\Request;
use App\Http\Controllers\StoreController;
use App\Http\Controllers\AddProductController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\TiketController;
use App\Http\Controllers\OrderController;


/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::post('/change-password', [AuthController::class, 'changePassword']);

Route::post('/register', [AuthController::class, 'register']);

// Route untuk mengambil semua tiket
Route::get('/tikets', [TiketController::class, 'index']);

// Route untuk membuat tiket baru
Route::post('/tikets', [TiketController::class, 'store']);

// Route untuk mendapatkan tiket berdasarkan ID
Route::get('/tikets/{id}', [TiketController::class, 'show']);

// Route untuk mengupdate tiket
Route::put('/tikets/{id}', [TiketController::class, 'update']);

// Route untuk menghapus tiket
Route::delete('/tikets/{id}', [TiketController::class, 'destroy']);


// Route untuk logout
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', function (Request $request) {
        return $request->user();
    });
    Route::post('/store', [StoreController::class, 'store']);
});

// Route untuk login
Route::post('/login', [AuthController::class, 'login']);

// Route untuk mengambil semua pengguna
Route::get('/users', [AuthController::class, 'index']);

// Route untuk mengambil data pengguna berdasarkan ID
Route::get('/user/{id}', [AuthController::class, 'getUserData']);
//Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//return $request->user();
//});


Route::get('/stores', [StoreController::class, 'index']);
Route::post('/stores', [StoreController::class, 'store']);
Route::get('/stores/{id}', [StoreController::class, 'show']);
Route::put('/stores/{id}', [StoreController::class, 'update']);
Route::delete('/stores/{id}', [StoreController::class, 'destroy']);


Route::get('/orders', [OrderController::class, 'index']);
Route::post('/orders', [OrderController::class, 'store']);
Route::put('/orders/{id}', [OrderController::class, 'update']);
Route::delete('/orders/{id}', [OrderController::class, 'destroy']);
Route::get('/orders/{id}', [OrderController::class, 'show']);


Route::get('/products', [AddProductController::class, 'index']); // GET semua produk
Route::post('/products', [AddProductController::class, 'store']); // POST produk baru
Route::get('/products/{id}', [AddProductController::class, 'show']); // GET produk by ID
Route::put('/products/{id}', [AddProductController::class, 'update']); // PUT update produk
Route::delete('/products/{id}', [AddProductController::class, 'destroy']); // DELETE produk
