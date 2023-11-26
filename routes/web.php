<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\AdminManagementController;
use App\Http\Controllers\AnalyticController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\VenueController;
use App\Http\Controllers\Auth\RegisterController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EntityController;
use App\Http\Controllers\ApprovalController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\PlanController;
use App\Http\Controllers\PreviewController;
use Illuminate\Support\Facades\Auth;

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
    Route::get('/admin/management', [AdminManagementController::class, 'index'])->name('admin.management');

    Route::get('/get-updated-event-counts', [ApprovalController::class, 'getUpdatedEventCounts'])->name('getUpdatedEventCounts');
    Route::get('/admin/approval', [ApprovalController::class, 'index'])->name('admin.approval');
    Route::get('/admin/get-events-approval', [ApprovalController::class, 'getApprovals']);
    Route::get('/admin/get-events-rejected', [ApprovalController::class, 'getRejected']);
    Route::post('/accept-event', [ApprovalController::class, 'acceptEvent']);
    Route::post('/reject-event', [ApprovalController::class, 'rejectEvent']);
    Route::post('/destroy-event', [ApprovalController::class, 'destroy']);
    Route::get('/admin/approval', [ApprovalController::class, 'index'])->name('admin.approval');
    
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

Route::middleware(['auth', 'user-access:User'])->group(function (){
    Route::get('/explore-events', [HomeController::class, 'home'])->name('home');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile/{id}/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile/{id}', [ProfileController::class, 'update'])->name('profile.update');
    Route::put('/profile/{id}/password', [ProfileController::class, 'changePassword'])->name('profile.password');
    Route::post('/upload/photo', [ProfileController::class, 'upload'])->name('upload.photo');
    Route::post('/profile/request', [ProfileController::class, 'createRequest'])->name('profile.request');

    Route::post('/events-feed', [AdminManagementController::class, 'getEvents'])->name('calendar.events');
    
    // Notification
    Route::post('/mark-read', [AdminManagementController::class, 'markRead'])->name('notification.read');
    
    // admin and event organizers
    Route::post('/admin/create-event', [AdminManagementController::class, 'store'])->name('admin.store_event');
    
    Route::get('/dashboard', [HomeController::class, 'adminHome'])->name('dashboard');
    Route::get('/getVenues/{campus}', [HomeController::class, 'getVenues']);
    Route::get('/projects', [ProjectController::class, 'index'])->name('projects');

    // Preview Event
    Route::get('/view/{eventId}', [PreviewController::class, 'index'])->name('preview');
    Route::post('/view/add-favorite', [PreviewController::class, 'updateHearts'])->name('preview.updateHearts');
    Route::post('/view/register', [PreviewController::class, 'registerEvent'])->name('preview.register');
    
    // Plan Event
    Route::get('/events/plan/{eventId?}', [PlanController::class, 'index'])->name('plan');
    Route::put('/events/{event}/update-description', [PlanController::class, 'updateDescription'])
    ->name('update.description');
    Route::put('/events/{event}/update-status', [PlanController::class, 'updateStatus'])
    ->name('update.status');
    Route::post('/events/reschedule', [PlanController::class, 'reschedule'])
    ->name('request.schedule');
    Route::post('/delete-event', [PlanController::class, 'deleteEvent'])->name('delete.event');
    Route::post('/add-flow', [PlanController::class, 'addFlow'])->name('event.add-flow');
    Route::post('/update-event-flow', [PlanController::class, 'updateFlow'])->name('event.update-flow');
    Route::delete('/delete-flow/{eventId}/{segmentId}/{flowId}', [PlanController::class, 'deleteFlow'])->name('event.delete-flow');
    Route::post('/add-person', [PlanController::class, 'addPerson'])->name('event.add-person');
    Route::post('/update-event-person', [PlanController::class, 'updatePerson'])->name('event.update-person');
    Route::delete('/delete-person/{eventId}/{personId}', [PlanController::class, 'deletePerson'])->name('event.delete-person');
    Route::get('/search-users', [PlanController::class, 'searchUsers'])->name('search.users');
    Route::post('/project/add-user', [PlanController::class, 'addUser'])->name('project.add-user');
    Route::post('/project/remove-user', [PlanController::class, 'removeUser'])->name('project.remove-user');
    Route::post('/upload/cover', [PlanController::class, 'upload'])->name('upload.cover');
    Route::post('/upload-images/{eventId}', [EventController::class, 'uploadImages'])->name('upload.images');
    
    // Analytics Event
    Route::get('/events/analytics/{eventId?}', [AnalyticController::class, 'index'])->name('analytics');


    // Attendance Event
    Route::get('/events/attendance/{eventId?}', [AttendanceController::class, 'index'])->name('attendance');
    Route::get('/events/get-attendance/{eventId}', [AttendanceController::class, 'getAttendanceData']);
    Route::post('/events/attended', [AttendanceController::class, 'attendanceCheck'])->name('attended');
});

