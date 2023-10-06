<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\AdminManagementController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\VenueController;
use App\Http\Controllers\Auth\RegisterController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EntityController;

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
    
    Route::post('/demote-admin', [AdminManagementController::class, 'demote']);
    Route::get('/admin/get-admins', [AdminManagementController::class, 'getAdmins']);
    Route::get('/admin/get-users', [AdminManagementController::class, 'getUsers']);
    Route::post('/update-is-disabled', [AdminManagementController::class, 'updateIsDisabled']);
    Route::post('/get-permission', [AdminManagementController::class, 'getPermission']);
    Route::post('/update-permission', [AdminManagementController::class, 'updatePermission']);
    
    Route::get('/admin/get-organizers', [AdminManagementController::class, 'getOrganizers']);
    Route::post('/promote-to-coadmin', [AdminManagementController::class, 'promoteToOrganizer']);

    Route::get('/admin/get-requests', [AdminManagementController::class, 'getRequests']);
    Route::post('/promote-to-organizer', [AdminManagementController::class, 'promoteToOrganizer']);
    Route::post('/reject/request', [AdminManagementController::class, 'rejectRequest']);
    // Route::post('/upload', [PdfFileController::class, 'upload']);
    // Route::get('/download/{id}', [PdfFileController::class, 'download']);
    
    Route::get('/admin/get-venues', [VenueController::class, 'getVenues']);
    Route::post('/get-selected-venue', [VenueController::class, 'getSelectedVenue']);
    Route::post('/update-venue', [VenueController::class, 'updateVenue']);
    Route::post('/update-venue-status', [VenueController::class, 'updateStatus']);
    Route::post('/delete-venue', [VenueController::class, 'destroy']);
    Route::post('/admin/create-venue', [VenueController::class, 'store'])->name('admin.store_venue');
    
    Route::get('/admin/get-entites', [EntityController::class, 'getEntities']);
    Route::post('/get-selected-entity', [EntityController::class, 'getSelectedEntity']);
    Route::post('/update-entity', [EntityController::class, 'updateEntity']);
    Route::post('/delete-entity', [EntityController::class, 'destroy']);
    Route::post('/admin/create-entity', [EntityController::class, 'store'])->name('admin.store_entity');
    
    Route::get('/admin/get-departments', [EntityController::class, 'getDepartments']);
    Route::post('/get-selected-department', [EntityController::class, 'getSelectedDepartment']);
    Route::post('/update-department', [EntityController::class, 'updateDepartment']);
    Route::post('/delete-department', [EntityController::class, 'destroyDepartment']);
    Route::post('/admin/create-department', [EntityController::class, 'storeDepartment'])->name('admin.store_department');

    Route::get('/admin/get-organizations', [EntityController::class, 'getOrganizations']);
    Route::post('/get-selected-organization', [EntityController::class, 'getSelectedOrganization']);
    Route::post('/update-organization', [EntityController::class, 'updateOrganization']);
    Route::post('/delete-organization', [EntityController::class, 'destroyOrganization']);
    Route::post('/admin/create-organization', [EntityController::class, 'storeOrganization'])->name('admin.store_organization');
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
