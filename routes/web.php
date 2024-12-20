<?php

use App\Http\Controllers\CipherController;
use App\Http\Controllers\FiatShamirController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\MailController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\PrimeController;
use App\Models\Cipher;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
Route::get('/', function () {
    return view('welcome');
});
Route::get('/about',[PageController::class,'about'])->name('about')->middleware('auth.basic');
Route::get('/animatsiya',[CipherController::class,'animatsiya'])->name('animatsiya');
Route::get('/create',[CipherController::class,'index'])->name('cipher')->middleware('auth');
Route::post('/create', [CipherController::class,'store'])->name('cipher')->middleware('auth');
Route::get('/sent-email',[MailController::class,'sendEmail'])->name('sendEmail');
Route::get('/key',[CipherController::class,'foydalanuvchi'])->name('key')->middleware('auth');
Route::get('/key-create',[CipherController::class,'shirkat'])->name('keycreate')->middleware('auth');
Route::post('/key-create',[CipherController::class,'shirkat'])->name('keycreate')->middleware('auth');
Route::get('/prime',[PrimeController::class,'generatePrime'])->name('prime')->middleware('auth');
Route::post('/download-prime', [PrimeController::class, 'downloadPrime'])->name('download.prime')->middleware('basic');
Route::post('/download-key', [PrimeController::class, 'downloadKey'])->name('download.key')->middleware('auth.basic');
Route::post('/download-kalit', [PrimeController::class, 'downloadKalit'])->name('download.kalit')->middleware('auth.basic');


Route::get('/fiat-shamir', [FiatShamirController::class, 'index'])->name('fiat-shamir.index');
Route::post('/fiat-shamir/process', [FiatShamirController::class, 'process'])->name('fiat-shamir.process');
//Route::get('/search-users', [CipherController::class, 'searchUsers'])->name('search.users');
Auth::routes();