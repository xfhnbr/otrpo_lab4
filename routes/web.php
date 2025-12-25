<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MuseumController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserController;

// просмотр всех музеев
Route::get('/', [MuseumController::class, 'index'])->name('home');
Route::get('/museums', [MuseumController::class, 'index'])->name('museums.index');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // маршруты пользователей
    Route::get('/users', [UserController::class, 'index'])->name('users.index');
    Route::get('/users/{identifier}', [UserController::class, 'show'])
        ->where('identifier', '[0-9]+|[\w\-\_]+')
        ->name('users.show');
    Route::get('/users/{identifier}/museums', [MuseumController::class, 'index'])
        ->where('identifier', '[0-9]+|[\w\-\_]+')
        ->name('users.museums.index');
    
    // маршруты для работы с корзиной (только для администраторов)
    Route::get('/museums/trash', [MuseumController::class, 'trash'])
        ->name('museums.trash')
        ->middleware('can:view-trash');
    
    Route::post('/museums/{id}/restore', [MuseumController::class, 'restore'])
        ->name('museums.restore')
        ->middleware('can:restore-museum');
    
    Route::delete('/museums/{id}/force-delete', [MuseumController::class, 'forceDelete'])
        ->name('museums.force-delete')
        ->middleware('can:force-delete-museum');
    
    Route::delete('/museums/trash/clear-all', [MuseumController::class, 'forceDeleteAll'])
        ->name('museums.forceDeleteAll')
        ->middleware('can:force-delete-all');
    
    // остальные CRUD маршруты
    Route::get('/museums/create', [MuseumController::class, 'create'])->name('museums.create');
    Route::post('/museums', [MuseumController::class, 'store'])->name('museums.store');
    Route::get('/museums/{museum}/edit', [MuseumController::class, 'edit'])->name('museums.edit');
    Route::put('/museums/{museum}', [MuseumController::class, 'update'])->name('museums.update');
    Route::patch('/museums/{museum}', [MuseumController::class, 'update']);
    Route::delete('/museums/{museum}', [MuseumController::class, 'destroy'])->name('museums.destroy');
});

// просмотр музея
Route::get('/museums/{id}', [MuseumController::class, 'show'])
    ->name('museums.show')
    ->where('id', '[0-9]+');    

require __DIR__.'/auth.php';