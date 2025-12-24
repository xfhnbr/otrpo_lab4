<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MuseumController;
use App\Http\Controllers\ProfileController;

Route::get('/', [MuseumController::class, 'index'])->name('home');

Route::get('/museums', [MuseumController::class, 'index'])->name('museums.index');

Route::get('/users/{user_id}/museums', [MuseumController::class, 'userMuseums'])
    ->name('users.museums.index');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    
    Route::get('/museums/trash', [MuseumController::class, 'trash'])->name('museums.trash');
    Route::post('/museums/{id}/restore', [MuseumController::class, 'restore'])->name('museums.restore');
    Route::delete('/museums/{id}/force-delete', [MuseumController::class, 'forceDelete'])->name('museums.force-delete');
    Route::delete('/museums/trash/clear-all', [MuseumController::class, 'forceDeleteAll'])->name('museums.forceDeleteAll');
    
    Route::resource('museums', MuseumController::class);
});

require __DIR__.'/auth.php';