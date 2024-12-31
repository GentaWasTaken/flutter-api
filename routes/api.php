<?php

use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\KategoriController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});



Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// Route yang bisa diakses oleh semua pengguna yang terautentikasi
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::patch('/users/{id}', [AuthController::class, 'update']);
    Route::get('/users', [AuthController::class, 'index']);

    // Menampilkan semua kategori
    Route::get('/kategori', [KategoriController::class, 'index']);

    // Menambahkan kategori baru
    Route::post('/kategori', [KategoriController::class, 'store']);

    // Menampilkan kategori berdasarkan ID
    Route::get('/kategori/{id}', [KategoriController::class, 'show']);

    // Memperbarui kategori berdasarkan ID
    Route::patch('/kategori/{id}', [KategoriController::class, 'update']);

    // Menghapus kategori berdasarkan ID
    Route::delete('/kategori/{id}', [KategoriController::class, 'destroy']);
});

// // Route untuk User (hanya dapat diakses oleh pengguna dengan role user)
// Route::middleware(['auth:sanctum', 'role:user'])->group(function () {

// });
