<?php

use App\Http\Controllers\Api\BarangController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\PelangganController;
use App\Http\Controllers\Api\PenjualanController;
use App\Http\Controllers\Api\ItemPenjualanController;

// Route::apiResource('pelanggan', PelangganController::class);

/// PELANGGAN
Route::post('/pelanggan', [PelangganController::class, 'store']);
Route::get('/pelanggan', [PelangganController::class, 'index']);
Route::get('/pelanggan/{id}', [PelangganController::class, 'show']);
Route::patch('/pelanggan/{id}', [PelangganController::class, 'update']);
Route::delete('/pelanggan/{id}', [PelangganController::class, 'destroy']);

/// BARANG
Route::post('/barang', [BarangController::class, 'store']);
Route::get('/barang', [BarangController::class, 'index']);
Route::get('/barang/{id}', [BarangController::class, 'show']);
Route::patch('/barang/{id}', [BarangController::class, 'update']);
Route::delete('/barang/{id}', [BarangController::class, 'destroy']);

/// PENJUALAN
Route::post('/penjualan', [PenjualanController::class, 'store']);
Route::get('/penjualan', [PenjualanController::class, 'index']);
Route::get('/penjualan/{id}', [PenjualanController::class, 'show']);
Route::patch('/penjualan/{id}', [PenjualanController::class, 'update']);
Route::delete('/penjualan/{id}', [PenjualanController::class, 'destroy']);

/// PENJUALAN
Route::post('/item-penjualan', [ItemPenjualanController::class, 'store']);
Route::get('/item-penjualan', [ItemPenjualanController::class, 'index']);
Route::get('/item-penjualan/{id}', [ItemPenjualanController::class, 'show']);
Route::patch('/item-penjualan/{id}', [ItemPenjualanController::class, 'update']);
Route::delete('/item-penjualan/{id}', [ItemPenjualanController::class, 'destroy']);

