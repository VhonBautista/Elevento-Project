<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\AdminManagementController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\VenueController;
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
    Route::get('/admin/management', [AdminManagementController::class, 'index'])->name('admin.management');
    Route::post('/admin/create-admin', [AdminManagementController::class, 'store'])->name('admin.store_admin');
    Route::get('/admin/get-admins', [AdminManagementController::class, 'getAdmins']);
    Route::get('/admin/get-users', [AdminManagementController::class, 'getUsers']);
    Route::post('/update-is-disabled', [AdminManagementController::class, 'updateIsDisabled']);
    Route::post('/get-permission', [AdminManagementController::class, 'getPermission']);
    Route::post('/update-permission', [AdminManagementController::class, 'updatePermission']);
    
    Route::get('/admin/get-venues', [VenueController::class, 'getVenues']);
    Route::post('/get-selected-venue', [VenueController::class, 'getSelectedVenue']);
    Route::post('/update-venue', [VenueController::class, 'updateVenue']);
    Route::post('/update-venue-status', [VenueController::class, 'updateStatus']);
    Route::post('/delete-venue', [VenueController::class, 'destroy']);
    Route::post('/admin/create-venue', [VenueController::class, 'store'])->name('admin.store_venue');
});

Route::middleware(['auth', 'user-access:Co-Admin'])->group(function (){
    Route::get('/co-admin/dashboard', [HomeController::class, 'adminHome'])->name('co_admin.dashboard');
    Route::get('/co-admin/management', [AdminManagementController::class, 'index'])->name('co_admin.management');
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

    Route::post('/events-feed', [AdminManagementController::class, 'getEvents'])->name('calendar.events');
});
