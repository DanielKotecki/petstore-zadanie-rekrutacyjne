<?php

use App\Http\Controllers\PetController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('pets.index');
});
Route::Resource('pets', PetController::class);
Route::get('/pets/{id}/upload', [PetController::class, 'uploadImageShow'])->name('pets.upload.show');
Route::post('/pets/upload', [PetController::class, 'uploadImage'])->name('pets.upload');
