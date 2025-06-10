<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\ProfileController;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [ContactController::class, 'index'])->name('dashboard');
    Route::post('/contacts/store', [ContactController::class, 'store'])->name('contacts.store');
    Route::get('/contacts/filter', [ContactController::class, 'filter'])->name('contacts.filter');
    Route::delete('/contacts/delete/{id}', [ContactController::class, 'destroy'])->name('contacts.destroy');
    Route::post('/contacts/merge', [ContactController::class, 'merge'])->name('contacts.merge');
    Route::get('/contacts/master-list/{id}', [ContactController::class, 'getMasterList'])->name('contacts.master_list');
});

require __DIR__.'/auth.php';
