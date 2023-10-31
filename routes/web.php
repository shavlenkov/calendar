<?php

use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/




Route::middleware('guest')->group(function() {
    Route::get('/signup', [AuthController::class, 'getSignup'])->name('get.signup');
    Route::post('/auth/signup', [AuthController::class, 'postSignup'])->name('post.signup');

    Route::get('/signin', [AuthController::class, 'getSignin'])->name('get.signin');
    Route::post('/auth/signin', [AuthController::class, 'postSignin'])->name('post.signin');
});

Route::get('/signout', [AuthController::class, 'getSignout'])->name('get.signout');

Route::middleware('auth')->group(function() {
    Route::get('/main', function () {
        return view('main');
    })->name('main');
});



