<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\BlogController;

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

Route::redirect('/', 'login');


Route::get('dashboard', [LoginController::class, 'dashboard'])->name('dashboard'); 
Route::get('login', [LoginController::class, 'index'])->name('login');
Route::post('custom-login', [LoginController::class, 'customLogin'])->name('login.custom'); 
Route::get('registration', [LoginController::class, 'registration'])->name('register-user');
Route::post('custom-registration', [LoginController::class, 'customRegistration'])->name('register.custom'); 
Route::get('signout', [LoginController::class, 'signOut'])->name('signout');

// Route::get('posts', [BlogController::class, 'index']);
Route::get('posts/create', [BlogController::class, 'create']);
Route::post('posts', [BlogController::class, 'store']);
Route::get('posts/{id}/edit', [BlogController::class, 'edit']);
Route::put('posts/{id}', [BlogController::class, 'update']);
Route::delete('posts/{id}', [BlogController::class, 'destroy']);


