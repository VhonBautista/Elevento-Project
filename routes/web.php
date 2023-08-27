<?php

use App\Http\Controllers\HomeController;
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

Route::middleware(['auth', 'user-access:admin'])->group(function (){
    Route::get('/admin/home', [HomeController::class, 'adminHome'])->name('admin.home');
});

Route::middleware(['auth', 'user-access:co_admin'])->group(function (){
    Route::get('/co-admin/home', [HomeController::class, 'adminHome'])->name('co_admin.home');
});

Route::middleware(['auth', 'user-access:organizer'])->group(function (){
    Route::get('/organizer/home', [HomeController::class, 'organizerHome'])->name('organizer.home');
});

Route::middleware(['auth', 'user-access:attendee'])->group(function (){
    Route::get('/home', [HomeController::class, 'attendeeHome'])->name('attendee.home');
});
