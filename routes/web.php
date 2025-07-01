<?php

use App\Http\Controllers\ArtikelController;
use App\Http\Controllers\AuthorController;
use App\Http\Controllers\LandingController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\KontakController;
use App\Http\Controllers\BeritaController;

Route::get('/katalog', [\App\Http\Controllers\KatalogController::class, 'index'])->name('katalog');
Route::get('/kontak', [KontakController::class, 'index'])->name('kontak');
Route::get('/artikel', [BeritaController::class, 'index'])->name('artikel');
Route::get('/', [LandingController::class,'index'])->name('landing');
Route::get('/artikels/{slug}', [ArtikelController::class,'show'])->name('artikels.show');

Route::get('/author/{username}',[AuthorController::class, 'show'])->name('author.show');