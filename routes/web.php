<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MuseumController;

Route::get('/', [MuseumController::class, 'index'])->name('home');

// Кастомные маршруты ДО resource
Route::get('/museums/trash', [MuseumController::class, 'trash'])->name('museums.trash');
Route::post('/museums/{id}/restore', [MuseumController::class, 'restore'])->name('museums.restore');
Route::delete('/museums/{id}/force-delete', [MuseumController::class, 'forceDelete'])->name('museums.force-delete');
Route::delete('/museums/trash/clear-all', [MuseumController::class, 'forceDeleteAll'])->name('museums.forceDeleteAll');

// Ресурсный маршрут ПОСЛЕ кастомных
Route::resource('museums', MuseumController::class);