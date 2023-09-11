<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Auth\RegisterController;
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

Route::middleware(['guest'])->group(function () {
    Route::get('/', function () {
        return view('auth.login');
    });
});

Auth::routes();

Route::post('/register', [RegisterController::class, 'register'])->name('register-user');

Route::middleware(['auth', 'user-access:Admin'])->group(function (){
    Route::get('/admin/dashboard', [HomeController::class, 'adminHome'])->name('admin.dashboard');
});

Route::middleware(['auth', 'user-access:Co-Admin'])->group(function (){
    Route::get('/co-admin/dashboard', [HomeController::class, 'adminHome'])->name('co_admin.dashboard');
});

Route::middleware(['auth', 'user-access:Organizer'])->group(function (){
    Route::get('/organizer/home', [HomeController::class, 'organizerHome'])->name('organizer.home');
});

Route::middleware(['auth', 'user-access:User'])->group(function (){
    Route::get('/home', [HomeController::class, 'home'])->name('home');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile/{id}/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile/{id}', [ProfileController::class, 'update'])->name('profile.update');
    Route::put('/profile/{id}/password', [ProfileController::class, 'changePassword'])->name('profile.password');
    Route::post('/upload/photo', [ProfileController::class, 'upload'])->name('upload.photo');
    Route::post('/profile/request', [ProfileController::class, 'createRequest'])->name('profile.request');
});
