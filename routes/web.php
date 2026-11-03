<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ReservasiController;

Route::get('/reservasi', [ReservasiController::class, 'index'])->name('reservasi.index');
Route::post('/reservasi', [ReservasiController::class, 'store'])->name('reservasi.store');
Route::get('/reservasi/{id}/edit', [ReservasiController::class, 'edit'])->name('reservasi.edit');
Route::put('/reservasi/{id}', [ReservasiController::class, 'update'])->name('reservasi.update');
Route::delete('/reservasi/{id}', [ReservasiController::class, 'destroy'])->name('reservasi.destroy');