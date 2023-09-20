<?php

use App\Http\Controllers\BeritaController;
use App\Http\Controllers\JadwalDonorDarahController;
use App\Http\Controllers\JadwalDonorPendonorController;
use App\Http\Controllers\PendonorController;
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

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::post("/login", [PendonorController::class, 'login']);
Route::get("/logout", [PendonorController::class, 'logout']);
Route::get("/home", [PendonorController::class, 'home']);
Route::get('/berita', [BeritaController::class, 'show']);

//untuk menu lokasi donor
Route::get('/jadwal-donor-darah', [JadwalDonorDarahController::class, 'show']);

Route::get('/jadwal-donor-pendonor/{id}/{idl}', [JadwalDonorPendonorController::class, 'check']);
Route::post('/jadwal-donor-pendonor', [JadwalDonorPendonorController::class, 'daftar']);
Route::get('/jadwal-donor-pendonor/{id}', [JadwalDonorPendonorController::class, 'find']);

Route::get('/profile', [PendonorController::class, 'showProfile']);
Route::post('/profile-edit-gambar', [PendonorController::class, 'updateGambar']);
Route::post('/profile-edit-data', [PendonorController::class, 'updateData']);
Route::post('/profile-edit-password', [PendonorController::class, 'editPassword']);