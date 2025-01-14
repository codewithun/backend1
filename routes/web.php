<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Response;

Route::get('/storage/{file}', function ($file) {
    $filePath = storage_path('app/public/products/' . $file);

    if (!file_exists($filePath)) {
        abort(404); // Handle the case where the file doesn't exist
    }

    // Use response()->make() to create a custom response and add headers
    $response = Response::make(file_get_contents($filePath), 200);
    $response->header('Content-Type', 'image/jpeg'); // Adjust to correct MIME type
    $response->header('Access-Control-Allow-Origin', '*');

    return $response;
});

Route::get('storage/{path}', function ($path) {
    return response()->file(storage_path('app/public/' . $path));
})->where('path', '.*');
