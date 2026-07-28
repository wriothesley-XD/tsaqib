<?php

use App\Http\Controllers\Api\PendaftaranController;
use Illuminate\Support\Facades\Route;

// Endpoint yang dipanggil dari Framer (lewat fetch di komponen Embed).
// URL lengkapnya nanti: https://domain-kamu.com/api/submit
Route::post('/submit', [PendaftaranController::class, 'store']);