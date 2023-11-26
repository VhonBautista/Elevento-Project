@extends('layouts.app')

@php
use Carbon\Carbon;
$user = Auth::user();
@endphp

@section('content')
<div class="side-menu">
<div class="sidebar">
        <div class="logo-content">
            <div class="logo">
                <i class="fa-solid fa-qrcode"></i>
                <div class="logo-name">ELEVENTO</div>
            </div>
            <i class="fa-solid fa-bars" id="menu-btn"></i>
        </div>
        <ul class="nav-list">
            <li>
                <a href="{{ route('dashboard') }}">
                    <i class="fa-solid fa-gauge"></i>
                    <span class="side-link-name">Home</span>
                </a>
            </li>
            <li>
                <a href="{{ route('projects') }}">
                    <i class="fa-solid fa-folder-open"></i>
                    <span class="side-link-name">Projects</span>
                </a>
            </li>
            @if (($user->role == 'Co-Admin' && $user->manage_event == 1) || $user->role == 'Admin')
            <li>
                <a href="{{ route('admin.approval') }}">
                    <i class="fa-solid fa-calendar-days"></i>
                    <span class="side-link-name">Event Approvals</span>
                </a>
            </li>
            @endif
            <!-- <li>
                <a href="">
                    <i class="fa-solid fa-note-sticky"></i>
                    <span class="side-link-name">Tasks</span>
                </a>
            </li> -->
            <li>
                <a href="{{ route('admin.management') }}" class="active">
                    <i class="fa-solid fa-toolbox"></i>
                    <span class="side-link-name">Utilities</span>
                </a>
            </li>
            <li>
                <a href="{{ route('profile.edit', ['id' => $user->id]) }}">
                    <i class="fa-solid fa-gear"></i>
                    <span class="side-link-name">Settings</span>
                </a>
            </li>
        </ul>

        <div class="profile-content">
            <div class="profile">
                <div class="profile-details">
                    <img src="@if (Auth::user()->profile_picture == null) {{ asset('asset/blank_profile.jpg') }} @else {{ asset(Auth::user()->profile_picture) }} @endif" alt="">
                    <div class="name-role">
                        <div class="user-name">{{ ucfirst($user->username) }}</div>
                        <div class="user-campus">{{ session('campus') }}</div>
                    </div>
                </div>
                <a class="dropdown-item" href="{{ route('logout') }}"
                onclick="event.preventDefault();
                                document.getElementById('logout-form').submit();">
                    <i class="fa-solid fa-arrow-right-from-bracket" id="logout"></i>
                </a>

                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                    @csrf
                </form>
            </div>
        </div>
    </div>

    <div class="admin-content"> 
        <ul class="nav px-3 py-2 justify-content-between bg-light">
            <li class="nav-item">
                <div class="d-flex align-items-center px-3 m-0 psu-logo">
                    <div class="circular-avatar me-2">
                        <img src="{{ asset('asset/psu_logo.png') }}" alt="Avatar">
                    </div>
                    <div class="row justify-content-center align-items-start psu-name">
                        <div class="col-md-12">
                            <p class="m-0 fw-bold" style="font-size: 12px;">
                                Pangasinan State University
                            </p>
                        </div>
                        <div class="col-md-12">
                            <p id="realtime" class="m-0" style="font-size: 12px;"></p>
                        </div>
                    </div>
                </div>
            </li>
            <div class="d-flex">
                <li class="nav-item me-2">
                    <a href="{{ route('home') }}" class="btn btn-primary px-4 rounded-pill mx-1">
                        <i class="fa-solid fa-globe me-2"></i>
                        Explore Events
                    </a>
                </li>
                <li class="nav-item ">
                    <div class="dropdown">
                        <button class="btn btn-primary rounded-pill position-relative dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fa-regular fa-bell"></i>
                            @if ($user->unreadNotifications->isNotEmpty())
                            <span class="position-absolute notification start-100 translate-middle badge rounded-pill bg-danger" style="height: 14px; width: 14px;">
                            </span>
                            @endif
                        </button>
                        <div class="dropdown-menu" style="max-height: 410px; overflow-y: auto;">
                            <h6 class="fw-bold py-1 text-center" style="font-size: 16px">Notifications</h6>
                            <hr class="m-0">
                            @forelse($user->unreadNotifications as $notification)
                                {{-- Notification Item --}}
                                <a class="dropdown-item mark-as-read" href="{{ url($notification->data['url']) }}" data-id="{{ $notification->id }}">
                                    <div class="d-flex align-items-center p-2" style="width: 365px">
                                        {{-- <div class="me-4">
                                            <!-- icon -->
                                            icon
                                        </div> --}}
                                        <div class="w-100">
                                            <div class="d-flex justify-content-between align-items-center">
                                                <p class="fw-bold m-0" style="font-size: 16px">{{ Illuminate\Support\Str::limit($notification->data['title'], 30) }}</p>
                                                <p class="fw-bold small m-0">{{ Carbon::parse($notification->created_at)->format('h:i A') }}</p>
                                            </div>
                                            <div class="row">
                                                <div class="col">
                                                    <p class="text-secondary small m-0">{{ Illuminate\Support\Str::limit($notification->data['message'],55) }}</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </a>
                                {{-- Notification Item End --}}
                            @empty
                                <div class="d-flex justify-content-center align-items-center" style="width: 365px">
                                    <div class="p-4">
                                        {{ __('There are no new notifications') }}
                                    </div>
                                </div>
                            @endforelse
                        </div>
                    </div>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link p-0 px-3 rounded-pill dropdown-toggle d-flex align-items-center" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <div class="circular-avatar">
                            <img src="@if ($user->profile_picture == null) {{ asset('asset/blank_profile.jpg') }} @else {{ asset($user->profile_picture) }} @endif">
                        </div>
                    </a>
    
                    <ul class="dropdown-menu mt-2" aria-labelledby="navbarDropdown">
                        <li><span class="dropdown-item">{{ ucfirst($user->username) }}</span></li>
                        <li><a class="dropdown-item" href="{{ route('profile.edit', ['id' => $user->id]) }}">Settings</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li>
                            <a class="dropdown-item" href="{{ route('logout') }}"
                    onclick="event.preventDefault();
                                    document.getElementById('logout-form').submit();">Logout</a>
                        </li>
                    </ul>
                </li>
            </div>
        </ul>

        <div class="container-fluid px-4 p-2">
            <ul class="nav nav-tabs pt-2" id="myTab" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link manage-tab active" id="manage-user" data-bs-toggle="tab" data-bs-target="#manage-user-content" type="button" role="tab" aria-controls="manage-user-content" aria-selected="true">User Management</button>
                </li>
                @if (($user->role == 'Co-Admin' && $user->manage_venue == 1) || $user->role == 'Admin')
                <li class="nav-item active" role="presentation">
                    <button class="nav-link manage-tab" id="manage-venue" data-bs-toggle="tab" data-bs-target="#manage-venue-content" type="button" role="tab" aria-controls="manage-venue-content" aria-selected="false">Venue Management</button>
                </li>
                @endif
                @if (($user->role == 'Co-Admin' && $user->manage_campus == 1) || $user->role == 'Admin')
                <li class="nav-item" role="presentation">
                    <button class="nav-link manage-tab" id="manage-campus" data-bs-toggle="tab" data-bs-target="#manage-campus-content" type="button" role="tab" aria-controls="manage-campus-content" aria-selected="false">Entity Management</button>
                </li>
                @endif
            </ul>

            <div class="tab-content" id="myTabContent">
                <div class="tab-pane fade p-2 show active" id="manage-user-content" role="tabpanel" aria-labelledby="manage-user-content">
                    <ul class="nav mt-2" style="height: 20px !important;">
                        @if ( $user->role == "Admin" )
                        <li class="nav-item">
                            <a class="nav-link p-0" href="#coAd">Manage Co Administrator</a>
                        </li>
                        <li class="nav-item">
                            <p class="nav-link text-dark px-2 py-0">|</p>
                        </li>
                        @endif
                        @if (($user->role == 'Co-Admin' && $user->manage_user == 1) || $user->role == 'Admin')
                        <li class="nav-item">
                            <a class="nav-link p-0" href="#organ">Manage Organizer</a>
                        </li>
                        <li class="nav-item">
                            <p class="nav-link text-dark px-2 py-0">|</p>
                        </li>
                        @endif
                        <li class="nav-item">
                            <a class="nav-link p-0" href="#user">Manage User</a>
                        </li>
                    </ul>

                    @if ( $user->role == "Admin" )
                    <span class="text fw-normal ms-0 liner" id="coAd">Manage Co Administrator</span>
                    <div class="row g-3 mb-4 mt-2 px-3">
                        <div class="col-md-9 mt-0 mb-3">
                            <div class="card">
                                <div class="d-flex justify-content-center align-items-center p-5" id="admin-loader">
                                    <div class="spinner-border text-primary" role="status">
                                        <span class="visually-hidden">Loading...</span>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <table id="admins-table" class="d-none">
                                        <thead style="overflow-x: scroll !important;">
                                            <tr>
                                                <th>Profile</th>
                                                <th>Department</th>
                                                <th>Permisions</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-3 mt-0">
                            <div class="card">
                                <div class="card-body">
                                    <span class="text-1 fw-normal ms-0">Add New Co-Admin</span>
                                    <div class="col-12 mt-3 mb-2 p-0">
                                        <button type="button" class="btn w-100 btn-primary" data-bs-toggle="modal" data-bs-target="#showOrganizerModal">Add</button>
                                    </div>
                                </div>
                                <div class="card-footer note m-0">
                                    <p class="text-muted">Note: When adding a new admin, you're granting them administrative privileges within the system. You can set specific permissions for that admin later.</p>
                                </div>
                            </div>
                        </div>

                        <!-- Add co-admin modal -->
                        <div class="modal fade" id="showOrganizerModal" tabindex="-1" aria-labelledby="showOrganizerModalLabel" aria-hidden="true">
                            <div class="modal-dialog modal-lg">
                                <div class="modal-content">
                                    <div class="modal-header bg-primary" style="padding: 6px 18px;">
                                        <i class="fa-solid me-2 fa-user text-light"></i>
                                        <h5 class="text-light m-0 modal-title">Add New Co-Admin</h5>
                                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="d-flex justify-content-center align-items-center p-5" id="organizer-loader">
                                            <div class="spinner-border text-primary" role="status">
                                                <span class="visually-hidden">Loading...</span>
                                            </div>
                                        </div>
                                        <table id="organizers-table" class="d-none">
                                            <thead style="overflow-x: scroll !important;">
                                                <tr>
                                                    <th>Profile</th>
                                                    <th>Department</th>
                                                    <th>Type</th>
                                                    <th style="width: 240px !important;">Actions</th>
                                                </tr>
                                        </table>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Permission form modal -->
                        <div class="modal fade" id="permissionModal" tabindex="-1" aria-labelledby="permissionModalLabel" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header bg-primary" style="padding: 6px 18px;">
                                        <i class="fa-solid me-2 fa-list-check text-light"></i>
                                        <h5 class="text-light m-0 modal-title">Manage Permissions</h5>
                                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="note mb-3">
                                            <p class="text-muted">
                                                Note: When you make someone a Co-Administrator, you can choose what they can do in the system. This lets you fine-tune their access, keeping things secure and efficient.
                                            </p>
                                        </div>
                                        <hr class="mb-4">
                                        <form id="update-permission-form" class="row px-2 g-3">
                                            @csrf
                                            <h5 class="form-label">Select Permissions</h5>
                                            <input type="hidden" id="selected_id" name="selected_id">
                                            <fieldset class="row mb-3 mt-2">
                                                <div class="col-sm-6 p-0 ps-3">
                                                    <div class="form-check">
                                                        <input type="checkbox" class="form-check-input" id="update_manage_user" name="update_manage_user">
                                                        <label class="form-check-label" for="update_manage_user">
                                                        Manage Organizer
                                                        </label>
                                                    </div>
                                                    <div class="form-check">
                                                        <input type="checkbox" class="form-check-input" id="update_manage_venue" name="update_manage_venue">
                                                        <label class="form-check-label" for="update_manage_venue">
                                                        Manage Venue
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="col-sm-6 p-0">
                                                    <div class="form-check disabled">
                                                        <input type="checkbox" class="form-check-input" id="update_manage_campus" name="update_manage_campus">
                                                        <label class="form-check-label" for="update_manage_campus">
                                                        Manage Campus
                                                        </label>
                                                    </div>
                                                    <div class="form-check disabled">
                                                        <input type="checkbox" class="form-check-input" id="update_manage_event" name="update_manage_event">
                                                        <label class="form-check-label" for="update_manage_event">
                                                        Manage Event
                                                        </label>
                                                    </div>
                                                </div>
                                            </fieldset>
                                        </form>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                        <button type="button" class="btn btn-primary" id="update-permission-btn">Save Changes</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif

                    @if (($user->role == 'Co-Admin' && $user->manage_user == 1) || $user->role == 'Admin')
                    <span class="text fw-normal ms-0 liner" id="organ">Manage Organizer</span>
                    <div class="row g-3 mb-4 mt-2 px-3">
                        <div class="col-md-12 card mt-0">
                            <div class="d-flex justify-content-center align-items-center p-5" id="request-loader">
                                <div class="spinner-border text-primary" role="status">
                                    <span class="visually-hidden">Loading...</span>
                                </div>
                            </div>
                            <div class="card-body">
                                <table id="requests-table" class="d-none">
                                    <thead style="overflow-x: scroll !important;">
                                        <tr>
                                            <th>Profile</th>
                                            <th>Department</th>
                                            <th>Organization</th>
                                            <th>Type</th>
                                            <th>Status</th>
                                            <th>Actions</th>
                                        </tr>
                                </table>
                            </div>
                        </div>
                    </div>

                    <!-- Modal -->
                    <div class="modal fade" id="rejectOrgModal" tabindex="-1" aria-labelledby="rejectOrgModalLabel" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header bg-primary" style="padding: 6px 18px;">
                                    <i class="fa-solid me-2 fa-circle-info text-light"></i>  
                                    <h5 class="text-light m-0 modal-title">Provide a Justification</h5>
                                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <form class="row g-3 px-2" id="justify-form">
                                    <div class="modal-body">
                                        @csrf
                                        <input type="hidden" id="rejected_id" name="rejected_id">
                                        <div class="col-12">
                                            <label for="inputAddress2" class="form-label">Reason</label>
                                            <textarea class="form-control" name="message" rows="5" style="resize: none;" id="reason" placeholder="Please provide the reason for rejecting this request"></textarea>
                                            <div id="reasonFeedback" class="invalid-feedback">Please provide a reason for rejection.</div>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-danger" data-bs-dismiss="modal" id="cancel-justify">Cancel</button>
                                        <button type="submit" class="btn btn-primary">Submit</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    @endif

                    <span class="text fw-normal ms-0 liner" id="user">Manage Users</span>
                    <div class="row g-3 mb-4 mt-2 px-3">
                        <div class="col-md-12 card mt-0">
                            <div class="d-flex justify-content-center align-items-center p-5" id="user-loader">
                                <div class="spinner-border text-primary" role="status">
                                    <span class="visually-hidden">Loading...</span>
                                </div>
                            </div>
                            <div class="card-body">
                                <table id="users-table" class="d-none">
                                    <thead style="overflow-x: scroll !important;">
                                        <tr>
                                            <th>Profile</th>
                                            <th>Sex</th>
                                            <th>Role</th>
                                            <th>Type</th>
                                            <th>Department</th>
                                            <th>Organization</th>
                                            <th>Actions</th>
                                        </tr>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                
                @if (($user->role == 'Co-Admin' && $user->manage_venue == 1) || $user->role == 'Admin')
                <div class="tab-pane fade p-2" id="manage-venue-content" role="tabpanel" aria-labelledby="manage-venue-content">
                    <span class="text fw-normal ms-0 liner">Manage Venue</span>
                    <div class="row g-3 mb-0 mt-2 px-3">
                        <div class="col-md-8 mb-3 mt-0">
                            <div class="card">
                                <div class="d-flex justify-content-center align-items-center p-5" id="venue-loader">
                                    <div class="spinner-border text-primary" role="status">
                                        <span class="visually-hidden">Loading...</span>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <table id="venues-table" class="d-none">
                                        <thead style="overflow-x: scroll !important;">
                                            <tr>
                                                <th>Image</th>
                                                <th>Name</th>
                                                <th>Handler</th>
                                                <th>Capacity</th>
                                                <th>Status</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <!-- Preview modal -->
                        <div class="modal fade" id="imagePreviewModal" tabindex="-1" aria-labelledby="imagePreviewModalLabel" aria-hidden="true">
                            <div class="modal-dialog">
                                <img id="previewImage" src="" alt="Preview Image" class="rounded" style="max-width: 100%;">
                            </div>
                        </div>

                        <!-- Venue form modal -->
                        <div class="modal fade" id="venueModal" tabindex="-1" aria-labelledby="venueModalLabel" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header bg-primary" style="padding: 6px 18px;">
                                        <i class="fa-solid fa-pen-to-square me-2 text-light"></i>
                                        <h5 class="text-light m-0 modal-title">Manage Venue Details</h5>
                                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <form id="update-venue-form" class="row px-2 g-3 pt-2">
                                            @csrf

                                            <input type="hidden" id="selected_venue_id" name="selected_venue_id">
                                            <div class="col-12 mt-2">
                                                <label for="update_venue" class="form-label">Venue Name</label>
                                                <input type="text" class="form-control" id="update_venue" placeholder="Enter venue name" name="update_venue">
                                            </div>
                                            
                                            <div class="col-12 mt-4">
                                                <label for="update_handler" class="form-label">Handler Name</label>
                                                <input type="text" class="form-control" id="update_handler" placeholder="Enter handler name" name="update_handler">
                                            </div>

                                            <div class="col-12 mt-4">
                                                <label for="update_capacity" class="form-label">Max Capacity</label>
                                                <input type="number" min="0" max="9999" class="form-control" id="update_capacity" placeholder="Enter max capacity for this venue" name="update_capacity">
                                            </div>
                                            
                                            <p class="mb-0">Venue Photo</p>
                                            <figure class="image-container-update rounded mt-2" style="height: 140px; width: 100%; overflow:hidden; display: none;">
                                                <img id="update_image_venue">
                                            </figure>

                                            <input type="file" id="upload-button-update" id="update-venue-photo-form" name="update_photo_venue" accept="image/*">
                                            <label for="upload-button-update" class="upload-label mt-2">
                                                <i class="fas fa-upload"></i> &nbsp; Select Photo
                                            </label>
                                        </form>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                        <button type="button" class="btn btn-primary" id="update-venue-btn">Save Changes</button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Venue form -->
                        <div class="col-md-4 mt-0">
                            <div class="card">
                                <div class="card-body">
                                    <span class="text-1 fw-normal ms-0">Add New Venue</span>
                                    <div class="alert alert-danger m-0 d-none alert-box" id="venue-form-error" role="alert"></div>
                                    <div class="alert alert-success m-0 d-none alert-box" id="venue-form-success" role="alert"></div>
                                    <form id="create-venue-form" class="row px-2 g-3 pt-2">
                                        @csrf
                                        <div class="col-12 mt-4">
                                            <label for="venue-form" class="form-label">Venue Name</label>
                                            <input type="text" class="form-control" id="venue-form" placeholder="Enter venue name" name="venue">
                                        </div>

                                        <div class="col-12 mt-4">
                                            <label for="handler-form" class="form-label">Handler Name</label>
                                            <input type="text" class="form-control" id="handler-form" placeholder="Enter handler name" name="handler">
                                        </div>

                                        <div class="col-12 mt-4">
                                            <label for="capacity-form" class="form-label">Max Capacity</label>
                                            <input type="number" min="0" max="9999" class="form-control" id="capacity-form" placeholder="Enter max capacity for this venue" name="capacity">
                                        </div>
                                        
                                        <p class="mb-0">Venue Photo</p>
                                        <figure class="image-container rounded mt-2" style="height: 140px; width: 100%; overflow:hidden; display: none;">
                                            <img id="chosen-image-venue">
                                        </figure>

                                        <input type="file" id="upload-button-venue" id="venue-photo-form" name="photo_venue" accept="image/*">
                                        <label for="upload-button-venue" class="upload-label mt-2">
                                            <i class="fas fa-upload"></i> &nbsp; Select Photo
                                        </label>
                                        <hr class="mt-3">
                                        <div class="col-12 mt-0 p-0">
                                            <button type="submit" class="btn w-100 btn-primary">Add</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @endif

                @if (($user->role == 'Co-Admin' && $user->manage_campus == 1) || $user->role == 'Admin')
                <div class="tab-pane fade p-2" id="manage-campus-content" role="tabpanel" aria-labelledby="manage-campus-content">
                    <ul class="nav mt-2" style="height: 20px !important;">
                        <li class="nav-item">
                            <a class="nav-link p-0" href="#entity">Manage Entity Record</a>
                        </li>
                        <li class="nav-item">
                            <p class="nav-link text-dark px-2 py-0">|</p>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link p-0" href="#dept">Manage Department</a>
                        </li>
                        <li class="nav-item">
                            <p class="nav-link text-dark px-2 py-0">|</p>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link p-0" href="#org">Manage Organization</a>
                        </li>
                    </ul>

                    <span class="text fw-normal ms-0 liner" id="entity">Manage Entity Record</span>
                    <div class="row g-3 mb-4 mt-0 px-3">
                        <div class="col-md-9 mt-0 mb-3">
                            <div class="card">
                                <div class="d-flex justify-content-center align-items-center p-5" id="entity-loader">
                                    <div class="spinner-border text-primary" role="status">
                                        <span class="visually-hidden">Loading...</span>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <table id="entities-table" class="d-none">
                                        <thead style="overflow-x: scroll !important;">
                                            <tr>
                                                <th>User ID</th>
                                                <th>Name</th>
                                                <th>Type</th>
                                                <th>Sex</th>
                                                <th>Campus</th>
                                                <th>Department</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-3 mt-0">
                            <div class="card">
                                <div class="card-body">
                                    <span class="text-1 fw-normal ms-0">Add New Record</span>
                                    <div class="col-12 mt-3 mb-2 p-0">
                                        <button type="button" class="btn w-100 btn-primary" data-bs-toggle="modal" data-bs-target="#showEntityModal">Add</button>
                                    </div>
                                </div>
                                <div class="card-footer note m-0">
                                    <p class="text-muted">Note: Creating a new entity record does not entail registering a new user in the system. The intention behind adding a new entity record is to verify, during user registration, whether they are affiliated with any PSU campuses.</p>
                                </div>
                            </div>
                        </div>

                        <!-- Add record modal -->
                        <div class="modal fade" id="showEntityModal" tabindex="-1" aria-labelledby="showEntityModalLabel" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header bg-primary" style="padding: 6px 18px;">
                                        <i class="fa-solid me-2 fa-user text-light"></i>
                                        <h5 class="text-light m-0 modal-title">Add New Record</h5>
                                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <form id="create-entity-form" class="row px-2 g-3 pt-2">
                                            @csrf
                                            
                                            <div class="col-md-12">
                                                <label for="user-id" class="form-label">User ID</label>
                                                <input type="text" class="form-control" id="user-id" placeholder="User ID" name="user_id">
                                                <div id="user-id-error" class="form-error mt-2 text-danger small d-none"></div>
                                            </div>

                                            <div class="col-md-12">
                                                <label for="firstname" class="form-label">Name</label>
                                                <div class="input-group">
                                                    <input type="text" class="form-control" id="firstname" placeholder="Firstname" name="firstname">
                                                    <input type="text" class="form-control" id="lastname" placeholder="Lastname" name="lastname">
                                                    <input type="text" class="form-control" id="middlename" placeholder="Middlename" name="middlename">
                                                </div>
                                                <div id="name-error" class="form-error mt-2 text-danger small d-none"></div>
                                            </div>

                                            <div class="col-md-12">
                                                <label for="entity-type" class="form-label">Type</label>
                                                <select class="form-select" id="entity-type" name="entity_type">
                                                    <option value="" selected>Select Type</option>
                                                    <option value="Student">Student</option>
                                                    <option value="Employee">Employee</option>
                                                </select>
                                                <div id="entity-type-error" class="form-error mt-2 text-danger small d-none"></div>
                                            </div>

                                            <div class="col-md-12">
                                                <label class="form-label">Sex</label>
                                                <div class="row ms-2">
                                                    <div class="form-check col-md-2">
                                                        <input class="form-check-input" type="radio" name="sex" id="male" value="Male">
                                                        <label class="form-check-label" for="male">Male</label>
                                                    </div>
                                                    <div class="form-check col-md-2">
                                                        <input class="form-check-input" type="radio" name="sex" id="female" value="Female">
                                                        <label class="form-check-label" for="female">Female</label>
                                                    </div>
                                                </div>
                                                <div id="sex-error" class="form-error mt-2 text-danger small d-none"></div>
                                            </div>

                                            <div class="col-md-12">
                                                <label for="department" class="form-label">Department</label>
                                                <select class="form-select" id="department" name="department">
                                                    <option value="" selected>Select Department</option>
                                                    @foreach ($departmentsData as $department)
                                                    <option value="{{ $department->department_code }}"> {{ $department->department }}</option>
                                                    @endforeach
                                                </select>
                                                <div id="department-error" class="form-error mt-2 text-danger small d-none"></div>
                                            </div>  
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                            <button type="submit" class="btn btn-primary">Add Record</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>

                        <!-- Update record modal -->
                        <div class="modal fade" id="updateEntityModal" tabindex="-1" aria-labelledby="updateEntityModalLabel" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header bg-primary" style="padding: 6px 18px;">
                                        <i class="fa-solid me-2 fa-user text-light"></i>
                                        <h5 class="text-light m-0 modal-title">Update Record</h5>
                                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <form id="update-entity-form" class="row px-2 g-3 pt-2">
                                            @csrf
                                            <input type="hidden" id="entity_id" name="entity_id">
                                            <div class="col-md-12">
                                                <label for="user-id" class="form-label">User ID</label>
                                                <input type="text" class="form-control" id="update-user-id" placeholder="User ID" name="update_user_id" disabled>
                                            </div>

                                            <div class="col-md-12">
                                                <label for="firstname" class="form-label">Name</label>
                                                <div class="input-group">
                                                    <input type="text" class="form-control" id="update-firstname" placeholder="Firstname" name="update_firstname">
                                                    <input type="text" class="form-control" id="update-lastname" placeholder="Lastname" name="update_lastname">
                                                    <input type="text" class="form-control" id="update-middlename" placeholder="Middlename" name="update_middlename">
                                                </div>
                                                <div id="update-name-error" class="update-form-error mt-2 text-danger small d-none"></div>
                                            </div>

                                            <div class="col-md-12">
                                                <label for="entity-type" class="form-label">Type</label>
                                                <select class="form-select" id="update-entity-type" name="update_entity_type">
                                                    <option value="" selected>Select Type</option>
                                                    <option value="Student">Student</option>
                                                    <option value="Employee">Employee</option>
                                                </select>
                                                <div id="update-entity-type-error" class="update-form-error mt-2 text-danger small d-none"></div>
                                            </div>

                                            <div class="col-md-12">
                                                <label class="form-label">Sex</label>
                                                <div class="row ms-2">
                                                    <div class="form-check col-md-2">
                                                        <input class="form-check-input" type="radio" name="update_sex" id="update-male" value="Male">
                                                        <label class="form-check-label" for="male">Male</label>
                                                    </div>
                                                    <div class="form-check col-md-2">
                                                        <input class="form-check-input" type="radio" name="update_sex" id="update-female" value="Female">
                                                        <label class="form-check-label" for="female">Female</label>
                                                    </div>
                                                </div>
                                                <div id="update-sex-error" class="update-form-error mt-2 text-danger small d-none"></div>
                                            </div>

                                            <div class="col-md-12">
                                                <label for="department" class="form-label">Department</label>
                                                <select class="form-select" id="update-department" name="update_department">
                                                    <option value="" selected>Select Department</option>
                                                    @foreach ($departmentsData as $department)
                                                    <option value="{{ $department->department_code }}"> {{ $department->department }}</option>
                                                    @endforeach
                                                </select>
                                                <div id="update-department-error" class="update-form-error mt-2 text-danger small d-none"></div>
                                            </div>  
                                        </form>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                        <button type="button" id="update-selected-entity" class="btn btn-primary">Update Record</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>  

                    <div class="row g-3 mb-4 mt-0 px-3">
                        <div class="col-md-12 mt-0 mb-5">
                            <span class="text fw-normal ms-0 liner" id="dept">Manage Department</span>
                            <div class="card mt-2" style="overflow-x: scroll !important;">
                                <div class="d-flex justify-content-center align-items-center p-5" id="department-loader">
                                    <div class="spinner-border text-primary" role="status">
                                        <span class="visually-hidden">Loading...</span>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <table id="departments-table" class="d-none">
                                        <thead style="overflow-x: scroll !important;">
                                            <tr>
                                                <th>Department Code</th>
                                                <th>Department</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                    </table>
                                </div>
                                <div class="card-footer">
                                    <div class="col-12 mt-3 mb-2 p-0">
                                        <button type="button" class="btn w-100 btn-primary" data-bs-toggle="modal" data-bs-target="#showDepartmentModal">Add New Department</button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-12 mt-0 mb-3">
                            <span class="text fw-normal ms-0 liner" id="org">Manage Organization</span>
                            <div class="card" style="overflow-x: scroll !important;">
                                <div class="d-flex justify-content-center align-items-center p-5" id="organization-loader">
                                    <div class="spinner-border text-primary" role="status">
                                        <span class="visually-hidden">Loading...</span>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <table id="organizations-table" class="d-none">
                                        <thead style="overflow-x: scroll !important;">
                                            <tr>
                                                <th>Organization Name</th>
                                                <th>Type</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                    </table>
                                </div>
                                <div class="card-footer">
                                    <div class="col-12 mt-3 mb-2 p-0">
                                        <button type="button" class="btn w-100 btn-primary" data-bs-toggle="modal" data-bs-target="#showOrganizationModal">Add New Organization</button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Add department modal -->
                        <div class="modal fade" id="showDepartmentModal" tabindex="-1" aria-labelledby="showDepartmentModalLabel" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header bg-primary" style="padding: 6px 18px;">
                                        <i class="fa-solid me-2 fa-user text-light"></i>
                                        <h5 class="text-light m-0 modal-title">Add New Department</h5>
                                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <form id="create-department-form" class="row px-2 g-3 pt-2">
                                            @csrf
                                            
                                            <div class="col-md-12">
                                                <label for="dept-code" class="form-label">Department Code</label>
                                                <input type="text" class="form-control" id="dept-code" placeholder="Enter department code" name="department_code">
                                                <div id="dept-code-error" class="form-error mt-2 text-danger small d-none"></div>
                                            </div>

                                            <div class="col-md-12">
                                                <label for="department-name" class="form-label">Department Name</label>
                                                <input type="text" class="form-control" id="department-name" placeholder="Enter department name" name="department_name">
                                                <div id="department-name-error" class="form-error mt-2 text-danger small d-none"></div>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                            <button type="submit" class="btn btn-primary">Add Department</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>

                        <!-- Add organization modal -->
                        <div class="modal fade" id="showOrganizationModal" tabindex="-1" aria-labelledby="showOrganizationModalLabel" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header bg-primary" style="padding: 6px 18px;">
                                        <i class="fa-solid me-2 fa-user text-light"></i>
                                        <h5 class="text-light m-0 modal-title">Add New Record</h5>
                                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <form id="create-organization-form" class="row px-2 g-3 pt-2">
                                            @csrf
                                            
                                            <div class="col-md-12">
                                                <label for="organization-name" class="form-label">Organization Name</label>
                                                <input type="text" class="form-control" id="organization-name" placeholder="Enter organization name" name="organization_name">
                                                <div id="organization-name-error" class="form-error mt-2 text-danger small d-none"></div>
                                            </div>
                                            
                                            <div class="col-md-12">
                                                <label for="organization-type" class="form-label">Type</label>
                                                <select class="form-select" id="organization-type" name="organization_type">
                                                    <option value="" selected>Select Type</option>
                                                    <option value="Student Organization">Student Organization</option>
                                                    <option value="Faculty Association">Faculty Association</option>
                                                    <option value="Non Faculty Association">Non Faculty Association</option>
                                                    <option value="National Organization">National Organization</option>
                                                </select>
                                                <div id="organization-type-error" class="form-error mt-2 text-danger small d-none"></div>
                                            </div>

                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                            <button type="submit" class="btn btn-primary">Add Organization</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>

                        <!-- Update department modal -->
                        <div class="modal fade" id="updateDepartmentModal" tabindex="-1" aria-labelledby="updateDepartmentModalLabel" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header bg-primary" style="padding: 6px 18px;">
                                        <i class="fa-solid me-2 fa-user text-light"></i>
                                        <h5 class="text-light m-0 modal-title">Update Department</h5>
                                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <form id="update-department-form" class="row px-2 g-3 pt-2">
                                            @csrf
                                            <input type="hidden" id="department_code" name="department_code">
                                            <div class="col-md-12">
                                                <label for="update-dept-code" class="form-label">Department Code</label>
                                                <input type="text" class="form-control" id="update-dept-code" placeholder="Enter department code" name="department_code" disabled>
                                            </div>

                                            <div class="col-md-12">
                                                <label for="update-department-name" class="form-label">Department Name</label>
                                                <input type="text" class="form-control" id="update-department-name" placeholder="Enter department name" name="update_department_name">
                                                <div id="update-department-name-error" class="form-error mt-2 text-danger small d-none"></div>
                                            </div>
                                        </form>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                        <button type="button" id="update-selected-department" class="btn btn-primary">Update Department</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Update organization modal -->
                        <div class="modal fade" id="updateOrganizationModal" tabindex="-1" aria-labelledby="updateOrganizationModalLabel" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header bg-primary" style="padding: 6px 18px;">
                                        <i class="fa-solid me-2 fa-user text-light"></i>
                                        <h5 class="text-light m-0 modal-title">Update Organization</h5>
                                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <form id="update-organization-form" class="row px-2 g-3 pt-2">
                                            @csrf
                                            <input type="hidden" id="organization_id" name="organization_id">
                                            <div class="col-md-12">
                                                <label for="update-organization-name" class="form-label">Organization Name</label>
                                                <input type="text" class="form-control" id="update-organization-name" placeholder="Enter organization name" name="update_organization_name">
                                                <div id="update-organization-name-error" class="form-error mt-2 text-danger small d-none"></div>
                                            </div>
                                            
                                            <div class="col-md-12">
                                                <label for="update-organization-type" class="form-label">Type</label>
                                                <select class="form-select" id="update-organization-type" name="update_organization_type">
                                                    <option value="" selected>Select Type</option>
                                                    <option value="Student Organization">Student Organization</option>
                                                    <option value="Faculty Association">Faculty Association</option>
                                                    <option value="Non Faculty Association">Non Faculty Association</option>
                                                    <option value="National Organization">National Organization</option>
                                                </select>
                                                <div id="update-organization-type-error" class="form-error mt-2 text-danger small d-none"></div>
                                            </div>
                                        </form>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                        <button type="button" id="update-selected-organization" class="btn btn-primary">Update Organization</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>  
                </div>
                @endif
            </div>
        </div>

        <footer class="py-4 bg-light mt-auto">
            <div class="container-fluid px-4">
                <div class="d-flex align-items-center justify-content-between small">
                    <div class="text-muted">Copyright &copy; 2023 Elevento Team. All Rights Reserved.</div>
                    <div>
                        <a href="#">Privacy Policy</a>
                        &middot;
                        <a href="#">Terms &amp; Conditions</a>
                    </div>
                </div>
            </div>
        </footer>
    </div>
</div>
@endsection

@section('script')
<script>
    $(document).ready(function() {
        let menubtn = $("#menu-btn");
        let sidebar = $(".sidebar");
        
        let isSidebarActive = getCookie("sidebarActive") === "true";
        if (isSidebarActive) {
            sidebar.addClass("active-sidebar");
        }
        
        let lastActiveTab = getCookie("activeTabMange");
        if (lastActiveTab) {
            $('.manage-tab').removeClass('active');
            $(`#${lastActiveTab}`).addClass('active');
            $('.tab-pane').removeClass('show active');
            $(`#${lastActiveTab}-content`).addClass('show active');
        }

        $('.manage-tab').click(function() {
            let activeTabId = $(this).attr('id');
            setCookie("activeTabMange", activeTabId, 4);
            let activeContentId = `${activeTabId}-content`;
            $('.tab-pane').removeClass('show active');
            $(`#${activeContentId}`).addClass('show active');
        });

        menubtn.click(function() {
            sidebar.toggleClass("active-sidebar");
            setCookie("sidebarActive", sidebar.hasClass("active-sidebar"), 4);
        });

        function getCookie(name) {
            let value = "; " + document.cookie;
            let parts = value.split("; " + name + "=");
            if (parts.length === 2) return parts.pop().split(";").shift();
        }

        function setCookie(name, value, hours) {
            let expires = new Date();
            expires.setTime(expires.getTime() + (hours * 60 * 60 * 1000));
            document.cookie = name + "=" + value + ";expires=" + expires.toUTCString() + ";path=/";
        }

        function clearCookies() {
            var cookies = document.cookie.split(";");

            for (var i = 0; i < cookies.length; i++) {
                var cookie = cookies[i];
                var eqPos = cookie.indexOf("=");
                var name = eqPos > -1 ? cookie.substr(0, eqPos) : cookie;
                document.cookie = name + "=;expires=Thu, 01 Jan 1970 00:00:00 GMT;path=/";
            }
        }
        
        const baseUrl = "{{ asset('') }}";

        // loader
        const adminLoader = $('#admin-loader');
        const organizerLoader = $('#organizer-loader');
        const requestLoader = $('#request-loader');
        const userLoader = $('#user-loader');
        
        // table
        const adminTable = $('#admins-table');
        const organizerTable = $('#organizers-table');
        const requestTable = $('#requests-table');
        const userTable = $('#users-table');

        // admins table
        adminLoader.addClass('d-none')
        adminTable.removeClass('d-none')
        var adminTableLoad = adminTable.DataTable({
            "scrollCollapse": true,
            "searching": true,
            "paging": true,
            "info": true,
            "lengthChange": true,
            "ordering": false,
            "scrollY": "400px",

            ajax: "{{ url('/admin/get-admins') }}",
            columns: [
                {
                    "data": null,
                    "render": function(data, type, full, meta) {
                        return `
                            <td>
                                <div class="row justify-content-center">
                                    <div class="col-md-3 text-center">
                                        <div style="width: 45px; height: 45px; overflow: hidden; border-radius: 50%; margin: 0 auto;">
                                            <img src="${baseUrl}${data.profile_picture ? data.profile_picture : 'asset/blank_profile.jpg'}" style="width: 100%; height: 100%; object-fit: cover;">
                                        </div>
                                    </div>

                                    <div class="col-md-8 ms-2 text-start">
                                        <p class="m-0">${data.username}</p>
                                        <p class="small text-muted m-0">${data.email}</p>
                                        <p class="d-none">${data.user_id}</p>
                                    </div>
                                </div>
                            </td>
                        `;
                    }
                },
                {"data" : "department_code"},
                {
                    "data": null,
                    "render": function(data, type, full, meta) {
                        return `
                            <td>
                                <div class="row"  style="width: 290px;">
                                    <div class="col-md-6">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" ${data.manage_user ? 'checked' : ''} style="pointer-events: none;">
                                            <label class="form-check-label">Manage User</label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" ${data.manage_venue ? 'checked' : ''} style="pointer-events: none;">
                                            <label class="form-check-label">Manage Venue</label>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" ${data.manage_campus ? 'checked' : ''} style="pointer-events: none;">
                                            <label class="form-check-label">Manage Entity</label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" ${data.manage_event ? 'checked' : ''} style="pointer-events: none;">
                                            <label class="form-check-label">Manage Event</label>
                                        </div>
                                    </div>
                                </div>
                            </td>
                        `;
                    }
                },
                {
                    "data": null,
                    "render": function(data, type, full, meta) {
                        return `
                            <div class="nav-item dropdown">
                                <a class="btn btn-dark dropdown-toggle" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                    Actions
                                </a>

                                <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                                    <div class="dropdown-item">
                                        <button type="button" data-id="${data.id}" class="btn w-100 btn-dark permission-btn" data-bs-toggle="modal" data-bs-target="#permissionModal">Manage Permissions</button>
                                    </div>
                                    <div class="dropdown-divider"></div>
                                    <button type="button" data-id="${data.id}" class="dropdown-item mb-1 w-100 text-center demote-btn">Remove Co-Admin</button>
                                </div>
                            </div>
                        `;
                    }
                }
            ]
        });
        
        adminTable.on('click', '.permission-btn', function() {
            $('#update-permission-form')[0].reset();
            $.ajax({
                url: "{{ url('/get-permission') }}",
                type: 'POST',
                data: {
                    "_token" : "{{ csrf_token() }}",
                    "id" : $(this).data('id')
                },
                dataType: 'json',
                success: function(response) {
                    $('#selected_id').val(response.data.id);
                    $('#update_manage_user').prop('checked', response.data.manage_user == 1);
                    $('#update_manage_venue').prop('checked', response.data.manage_venue == 1);
                    $('#update_manage_campus').prop('checked', response.data.manage_campus == 1);
                    $('#update_manage_event').prop('checked', response.data.manage_event == 1);
                }
            });
        });

        adminTable.on('click', '.demote-btn', function() {
            Swal.fire({
                title: 'Remove Co-Administrator?',
                text: 'This will remove all the user\'s administrative privileges.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#aaa',
                confirmButtonText: 'Yes'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: "{{ url('/demote-admin') }}",
                        type: 'POST',
                        data: {
                            "_token" : "{{ csrf_token() }}",
                            "id" : $(this).data('id')
                        },
                        dataType: 'json',
                        success: function(response) {
                            organizerTableLoad.ajax.reload();
                            adminTableLoad.ajax.reload();
                            Swal.fire({
                                icon: 'success',
                                title: 'Success',
                                text: 'Co-Administrator Removed Successfully!',
                                confirmButtonColor: '#3085d6',
                                confirmButtonText: 'OK'
                            });
                        }
                    });
                }
            });
        });

        $('#update-permission-btn').on('click', function() {
            Swal.fire({
                title: 'Update Permission?',
                text: 'Set new permissions to this admin?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Confirm'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: "{{ url('/update-permission') }}",
                        type: 'POST',
                        data: $('#update-permission-form').serialize(),
                        dataType: 'json',
                        success: function(response) {
                            adminTableLoad.ajax.reload();
                            $('#update-permission-form')[0].reset();
                            $('#permissionModal').modal('hide');
                            Swal.fire({
                                icon: 'success',
                                title: 'Update Complete',
                                confirmButtonColor: '#3085d6',
                                confirmButtonText: 'OK'
                            });
                        }
                    });
                }
            });
        });
        
        // organizers table
        organizerLoader.addClass('d-none')
        organizerTable.removeClass('d-none')
        var organizerTableLoad = organizerTable.DataTable({
            "scrollCollapse": true,
            "searching": true,
            "paging": true,
            "info": true,
            "lengthChange": false,
            "ordering": false,
            "scrollY": "280px",

            ajax: "{{ url('/admin/get-organizers') }}",
            columns: [
                {
                    "data": null,
                    "render": function(data, type, full, meta) {
                        return `
                            <td>
                                <div class="row justify-content-center">
                                    <div class="col-md-3 text-center">
                                        <div style="width: 45px; height: 45px; overflow: hidden; border-radius: 50%; margin: 0 auto;">
                                            <img src="${baseUrl}${data.profile_picture ? data.profile_picture : 'asset/blank_profile.jpg'}" style="width: 100%; height: 100%; object-fit: cover;">
                                        </div>
                                    </div>

                                    <div class="col-md-8 ms-2 text-start">
                                        <p class="m-0">${data.username}</p>
                                        <p class="small text-muted m-0">${data.email}</p>
                                        <p class="d-none">${data.user_id}</p>
                                    </div>
                                </div>
                            </td>
                        `;
                    }
                },
                {"data": "department_code"},
                {
                    "data": null,
                    "render": function(data, type, full, meta) {
                        let backgroundColor;
                        switch(data.type) {
                            case 'Employee':
                                backgroundColor = '#3a6bdd';
                                break;
                            default:
                                backgroundColor = '#ffc107';
                        }
                        return `
                            <td>
                                <p class="m-0 px-3 rounded-pill text-light text-center"
                                    style="width: 100px; background-color: ${backgroundColor};">
                                    ${data.type}
                                </p>
                            </td>
                        `;
                    }
                },
                {
                    "data": null,
                    "render": function(data, type, row, meta) {
                        return `<button type="button" data-id="${data.id}" style="width: 170px !important;" class="btn btn-dark">Promote to Co-Admin</button>`;
                    }
                }
            ]
        });

        organizerTable.on('click', '.btn-dark', function() {
            Swal.fire({
                title: 'Promote to Co-Admin?',
                text: 'Grant user administrative privileges within the system?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: "{{ url('/promote-to-coadmin') }}",
                        type: 'POST',
                        data: {
                            "_token" : "{{ csrf_token() }}",
                            "id" : $(this).data('id')
                        },
                        dataType: 'json',
                        success: function(response) {
                            if (response.success) {
                                organizerTableLoad.ajax.reload();
                                adminTableLoad.ajax.reload();
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Success',
                                    text: 'User was promoted to co-administrator!',
                                    confirmButtonColor: '#3085d6',
                                    confirmButtonText: 'OK'
                                });
                            } else {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Oops...',
                                    text: 'Something went wrong.',
                                });
                            }
                        }
                    });
                }
            });
        });
        
        // requests table
        requestLoader.addClass('d-none')
        requestTable.removeClass('d-none')
        var requestTableLoad = requestTable.DataTable({
            "scrollCollapse": true,
            "searching": true,
            "paging": true,
            "info": true,
            "lengthChange": true,
            "ordering": false,
            "scrollY": "400px",
            
            ajax: "{{ url('/admin/get-requests') }}",
            columns: [
                {
                    "data": null,
                    "render": function(data, type, full, meta) {
                        return `
                            <td>
                                <div class="row justify-content-center" style="width: 220px;">
                                    <div class="col-md-3 text-center">
                                        <div style="width: 45px; height: 45px; overflow: hidden; border-radius: 50%; margin: 0 auto;">
                                            <img src="${baseUrl}${data.profile_picture ? data.profile_picture : 'asset/blank_profile.jpg'}" style="width: 100%; height: 100%; object-fit: cover;">
                                        </div>
                                    </div>

                                    <div class="col-md-8 ms-2 text-start">
                                        <p class="m-0">${data.username}</p>
                                        <p class="small text-muted m-0">${data.email}</p>
                                        <p class="d-none">${data.user_id}</p>
                                    </div>
                                </div>
                            </td>
                        `;
                    }
                },
                {"data": "department_code"},
                {
                    "data": null,
                    "render": function(data, type, full, meta) {
                        return `
                            <td>
                                <div style="width: 200px;">
                                    <p class="mb-0">${data.organization}</p>
                                </div>
                            </td>
                        `;
                    }
                },
                {
                    "data": null,
                    "render": function(data, type, full, meta) {
                        let backgroundColor;
                        switch(data.type) {
                            case 'Employee':
                                backgroundColor = '#3a6bdd';
                                break;
                            default:
                                backgroundColor = '#ffc107';
                        }
                        return `
                            <td>
                                <p class="m-0 px-3 rounded-pill text-light text-center"
                                    style="width: 100px; background-color: ${backgroundColor};">
                                    ${data.type}
                                </p>
                            </td>
                        `;
                    }
                },
                {
                    "data": null,
                    "render": function(data, type, full, meta) {
                        let backgroundColor;
                        switch(data.status) {
                            case 'Pending':
                                backgroundColor = '#3a6bdd';
                                break;
                            case 'Active':
                                backgroundColor = '#198754';
                                break;
                            default:
                                backgroundColor = '#d33';
                        }
                        return `
                            <td>
                                <p class="m-0 px-3 rounded-pill text-light text-center"
                                    style="width: 100px; background-color: ${backgroundColor};">
                                    ${data.status}
                                </p>
                            </td>
                        `;
                    }
                },
                {
                    "data": null,
                    "render": function(data, type, full, meta) {
                        return `
                            <td>
                                <button type="button" class="btn btn-dark" data-bs-toggle="modal" data-bs-target="#detailsModal" style="width: 200px;">
                                    View Request Details
                                </button>

                                <!-- Modal -->
                                <div class="modal fade" id="detailsModal" tabindex="-1" aria-labelledby="detailsModalLabel" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header bg-primary" style="padding: 6px 18px;">
                                                <i class="fa-solid me-2 fa-circle-info text-light"></i>  
                                                <h5 class="text-light m-0 modal-title">Request Details</h5>
                                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <div class="row g-3">
                                                    <div class="col-12">
                                                        <label for="departmentDetail" class="form-label">Department</label>
                                                        <input type="text" class="form-control" id="departmentDetail" readonly value="${data.department}">
                                                    </div>
                                                    <div class="col-12">
                                                        <label for="organizationDetail" class="form-label">Organization</label>
                                                        <input type="text" class="form-control" id="organizationDetail" readonly value="${data.organization}">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" data-id="${data.id}" class="btn btn-danger reject-request-btn" data-bs-toggle="modal" data-bs-target="#rejectOrgModal">
                                                    Reject
                                                </button>
                                                <button type="button" data-id="${data.id}" data-userid="${data.userId}" class="btn btn-dark promote-org-btn">Promote to Organizer</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </td>
                        `;
                    }
                },
            ]
        });

        requestTable.on('click', '.promote-org-btn', function() {
            Swal.fire({
                title: 'Promote to Organizer?',
                text: 'Grant user the ability to create events within the system?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes'
            }).then((result) => {
                if (result.isConfirmed) {
                    $('#detailsModal').modal('hide');
                    $.ajax({
                        url: "{{ url('/promote-to-organizer') }}",
                        type: 'POST',
                        data: {
                            "_token" : "{{ csrf_token() }}",
                            "id" : $(this).data('id'),
                            "userId" : $(this).data('userid')
                        },
                        dataType: 'json',
                        success: function(response) {
                            if (response.success) {
                                requestTableLoad.ajax.reload();
                                userTableLoad.ajax.reload();
                                organizerTableLoad.ajax.reload();
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Success',
                                    text: 'User was promoted to organizer!',
                                    confirmButtonColor: '#3085d6',
                                    confirmButtonText: 'OK'
                                });
                            } else {
                                console.log(response.user);
                                console.log(response.request);
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Oops...',
                                    text: 'Something went wrong.',
                                });
                            }
                        }
                    });
                }
            });
        });

        requestTable.on('click', '.reject-request-btn', function() {
            console.log($(this).data('id'));
            $('#rejected_id').val($(this).data('id'));
        });

        $('#justify-form').submit(function(event) {
            event.preventDefault();
            
            if ($('#reason').val() == '') {
                $('#reason').addClass('is-invalid');
                $('#reasonFeedback').show();
            } else {
                $('#reason').removeClass('is-invalid');
                $('#reasonFeedback').hide();
                $.ajax({
                    type: 'POST',
                    url: "{{ url('/reject/request') }}",
                    data: $(this).serialize(),
                    success: function(response) {
                        requestTableLoad.ajax.reload();
                        $('#rejectOrgModal').modal('hide');
                        $('#justify-form')[0].reset();
                        Swal.fire({
                            icon: 'success',
                            title: 'Success',
                            text: 'Thank you for providing your justification. Your input has been successfully received.',
                            confirmButtonColor: '#3085d6',
                            confirmButtonText: 'OK'
                        });
                    },
                });
            }
        });
        
        $('#cancel-justify').click(function() {
            Swal.fire({
                icon: 'warning',
                title: 'Rejection Cancelled',
                timer: 1500,
                timerProgressBar: true,
                showConfirmButton: false
            });
        });

        // users table
        userLoader.addClass('d-none')
        userTable.removeClass('d-none')
        var userTableLoad = userTable.DataTable({
            "scrollCollapse": true,
            "searching": true,
            "paging": true,
            "info": true,
            "lengthChange": true,
            "ordering": false,
            "scrollY": "400px",

            ajax: "{{ url('/admin/get-users') }}",
            columns: [
                {
                    "data": null,
                    "render": function(data, type, full, meta) {
                        return `
                            <td>
                                <div class="row justify-content-center">
                                    <div class="col-md-3 text-center">
                                        <div style="width: 45px; height: 45px; overflow: hidden; border-radius: 50%; margin: 0 auto;">
                                            <img src="${baseUrl}${data.profile_picture ? data.profile_picture : 'asset/blank_profile.jpg'}" style="width: 100%; height: 100%; object-fit: cover;">
                                        </div>
                                    </div>

                                    <div class="col-md-8 ms-2 text-start">
                                        <p class="m-0">${data.username}</p>
                                        <p class="small text-muted m-0">${data.email}</p>
                                        <p class="d-none">${data.user_id}</p>
                                    </div>
                                </div>
                            </td>
                        `;
                    }
                },
                {"data": "sex"},
                {
                    "data": null,
                    "render": function(data, type, full, meta) {
                        let backgroundColor;
                        switch(data.role) {
                            case 'Admin':
                            case 'Co-Admin':
                                backgroundColor = '#3a6bdd';
                                break;
                            case 'Organizer':
                                backgroundColor = '#198754';
                                break;
                            default:
                                backgroundColor = '#ffc107';
                        }
                        return `
                            <td>
                                <p class="m-0 px-3 rounded-pill text-light text-center"
                                    style="width: 100px; background-color: ${backgroundColor};">
                                    ${data.role}
                                </p>
                            </td>
                        `;
                    }
                },
                {
                    "data": null,
                    "render": function(data, type, full, meta) {
                        let backgroundColor;
                        switch(data.type) {
                            case 'Employee':
                                backgroundColor = '#3a6bdd';
                                break;
                            default:
                                backgroundColor = '#ffc107';
                        }
                        return `
                            <td>
                                <p class="m-0 px-3 rounded-pill text-light text-center"
                                    style="width: 100px; background-color: ${backgroundColor};">
                                    ${data.type}
                                </p>
                            </td>
                        `;
                    }
                },
                {"data": "department_code"},
                {
                    "data": "organization",
                    "render": function(data, type, row, meta) {
                        if (data) {
                            return data;
                        } else {
                            return `<span class="text-muted">Not joined</span>`;
                        }
                    }
                },
                {
                    "data": null,
                    "render": function(data, type, row, meta) {
                        if (data.is_disabled) {
                            return `<button type="button" data-id="${data.id}" class="btn btn-success">Activate</button>`;
                        } else {
                            return `<button type="button" data-id="${data.id}" class="btn btn-danger">Disable</button>`;
                        }
                    }
                }
            ]
        });

        userTable.on('click', '.btn-success', function() {
            Swal.fire({
                title: 'Activate User?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Activate'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: "{{ url('/update-is-disabled') }}",
                        type: 'POST',
                        data: {
                            "_token" : "{{ csrf_token() }}",
                            "id" : $(this).data('id')
                        },
                        dataType: 'json',
                        success: function(response) {
                            if (response.success) {
                                userTableLoad.ajax.reload();
                            } else {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Oops...',
                                    text: 'Something went wrong.',
                                });
                            }
                        }
                    });
                }
            });
        });

        userTable.on('click', '.btn-danger', function() {
            Swal.fire({
                title: 'Disable this user?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Disable'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: "{{ url('/update-is-disabled') }}",
                        type: 'POST',
                        data: {
                            "_token" : "{{ csrf_token() }}",
                            "id" : $(this).data('id')
                        },
                        dataType: 'json',
                        success: function(response) {
                            if (response.success) {
                                userTableLoad.ajax.reload();
                            } else {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Oops...',
                                    text: 'Something went wrong.',
                                });
                            }
                        }
                    });
                }
            });
        });

        // VENUE TAB
        // upload photo
        $("#upload-button-venue").change(function() {
            let reader = new FileReader();

            if (this.files[0] instanceof Blob) {
                reader.readAsDataURL(this.files[0]);
                reader.onload = function() {
                    $("#chosen-image-venue").attr("src", reader.result);
                    $(".image-container").show();
                };
            }
        });

        $("#upload-button-update").change(function() {
            let reader = new FileReader();
            reader.readAsDataURL(this.files[0]);
            reader.onload = function() {
                $("#update_image_venue").attr("src", reader.result);
                $(".image-container-update").show();
            };
        });

        // venues table
        const venueLoader = $('#venue-loader');
        const venueTable = $('#venues-table');

        venueLoader.addClass('d-none')
        venueTable.removeClass('d-none')
        var venueTableLoad = venueTable.DataTable({
            "scrollCollapse": true,
            "searching": true,
            "paging": true,
            "info": true,
            "lengthChange": true,
            "ordering": false,
            "scrollY": "400px",

            ajax: "{{ url('/admin/get-venues') }}",
            columns: [
                {
                    "data": null,
                    "render": function(data, type, full, meta) {
                        return `
                            <td>
                                <div style="width: 170px; height: 80px; overflow: hidden; border-radius: 8px; margin: 0 auto;">
                                    <img src="${baseUrl}${data.image ? data.image : 'asset/blank_photo.jpg'}" style="width: 100%; height: 100%; object-fit: cover; cursor: pointer;" class="preview-venue-image">
                                </div>
                            </td>
                        `;
                    }
                },
                {"data" : "venue_name"},
                {"data" : "handler_name"},
                {"data" : "capacity"},
                {
                    "data": null,
                    "render": function(data, type, full, meta) {
                        let backgroundColor;
                        switch(data.status) {
                            case 'Active':
                                backgroundColor = '#198754';
                                break;
                            default:
                                backgroundColor = '#dc3545';
                        }
                        return `
                            <td>
                                <p class="m-0 px-3 rounded-pill text-light text-center"
                                    style="width: 100px; background-color: ${backgroundColor};">
                                    ${data.status}
                                </p>
                                <p class="d-none">
                                    ${data.campus}
                                </p>
                            </td>
                        `;
                    }
                },
                {
                    "data": null,
                    "render": function(data, type, row, meta) {
                        if (data.status == "Inactive") {
                            return `
                            <div class="nav-item dropdown">
                                <a class="btn btn-dark dropdown-toggle" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                    Actions
                                </a>

                                <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                                    <div class="dropdown-item">
                                        <button type="button" data-id="${data.id}" class="btn btn-success w-100">Activate</button>
                                    </div>
                                    <div class="dropdown-divider"></div>
                                    <button type="button" data-id="${data.id}" class="dropdown-item mb-1 w-100 text-center edit-btn" data-bs-toggle="modal" data-bs-target="#venueModal">Edit</button>
                                    <button type="button" data-id="${data.id}" class="dropdown-item mb-1 w-100 text-center delete-btn">Delete</button>
                                </div>
                            </div>
                            `;
                        } else {
                            return `
                            <div class="nav-item dropdown">
                                <a class="btn btn-dark dropdown-toggle" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                    Actions
                                </a>

                                <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                                    <div class="dropdown-item">
                                        <button type="button" data-id="${data.id}" class="btn btn-danger w-100">Disable</button>
                                    </div>
                                    <div class="dropdown-divider"></div>
                                    <button type="button" data-id="${data.id}" class="dropdown-item mb-1 w-100 text-center edit-btn" data-bs-toggle="modal" data-bs-target="#venueModal">Edit</button>
                                    <button type="button" data-id="${data.id}" class="dropdown-item mb-1 w-100 text-center delete-btn">Delete</button>
                                </div>
                            </div>
                            `;
                        }
                    }
                }
            ]
        });

        venueTable.on('click', '.preview-venue-image', function() {
            var imageUrl = $(this).attr('src');
            console.log(imageUrl);
            $('#imagePreviewModal').modal('show');
            $('#previewImage').attr('src', imageUrl);
        });

        venueTable.on('click', '.btn-success', function() {
            Swal.fire({
                title: 'Activate Venue?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: "{{ url('/update-venue-status') }}",
                        type: 'POST',
                        data: {
                            "_token" : "{{ csrf_token() }}",
                            "id" : $(this).data('id')
                        },
                        dataType: 'json',
                        success: function(response) {
                            if (response.success) {
                                venueTableLoad.ajax.reload();
                            } else {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Oops...',
                                    text: 'Something went wrong.',
                                });
                            }
                        }
                    });
                }
            });
        });

        venueTable.on('click', '.btn-danger', function() {
            Swal.fire({
                title: 'Disable Venue?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: "{{ url('/update-venue-status') }}",
                        type: 'POST',
                        data: {
                            "_token" : "{{ csrf_token() }}",
                            "id" : $(this).data('id')
                        },
                        dataType: 'json',
                        success: function(response) {
                            if (response.success) {
                                venueTableLoad.ajax.reload();
                            } else {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Oops...',
                                    text: 'Something went wrong.',
                                });
                            }
                        }
                    });
                }
            });
        });

        venueTable.on('click', '.delete-btn', function() {
            Swal.fire({
                title: 'Delete Venue',
                text: 'Please note that this action cannot be undone.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#aaa',
                confirmButtonText: 'Delete'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: "{{ url('/delete-venue') }}",
                        type: 'POST',
                        data: {
                            "_token" : "{{ csrf_token() }}",
                            "id" : $(this).data('id')
                        },
                        dataType: 'json',
                        success: function(response) {
                            venueTableLoad.ajax.reload();
                            Swal.fire({
                                icon: 'success',
                                title: 'Deleted',
                                text: 'Venue was deleted successfully!',
                                confirmButtonColor: '#3085d6',
                                confirmButtonText: 'OK'
                            });
                        }
                    });
                }
            });
        });

        venueTable.on('click', '.edit-btn', function() {
            $('#update-venue-form')[0].reset();
            $.ajax({
                url: "{{ url('/get-selected-venue') }}",
                type: 'POST',
                data: {
                    "_token" : "{{ csrf_token() }}",
                    "id" : $(this).data('id')
                },
                dataType: 'json',
                success: function(response) {
                    $('#selected_venue_id').val(response.data.id);
                    $('#update_venue').val(response.data.venue_name);
                    $('#update_handler').val(response.data.handler_name);
                    $('#update_capacity').val(response.data.capacity);
                    $('#update_image_venue').attr('src', response.data.image ? `${baseUrl}${response.data.image}` : `${baseUrl}asset/blank_photo.jpg`);
                    $(".image-container-update").show();
                }
            });
        });

        $('#update-venue-btn').on('click', function() {
            Swal.fire({
                title: 'Update Venue?',
                text: 'Update venue details?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Confirm'
            }).then((result) => {
                if (result.isConfirmed) {
                    var formData = new FormData($('#update-venue-form')[0]);

                    $.ajax({
                        url: "{{ url('/update-venue') }}",
                        type: 'POST',
                        dataType: 'json',
                        data: formData,
                        contentType: false,
                        processData: false,
                        success: function(response) {
                            $('#update-venue-form')[0].reset();
                            venueTableLoad.ajax.reload();
                            $('#venueModal').modal('hide');
                            Swal.fire({
                                icon: 'success',
                                title: 'Update Complete',
                                confirmButtonColor: '#3085d6',
                                confirmButtonText: 'OK'
                            });
                        }
                    });
                }
            });
        });

        // create venue
        $('#create-venue-form').submit(function(event) {
            event.preventDefault();

            var venue = $('#venue-form').val();
            var handler = $('#handler-form').val();
            var capacity = $('#capacity-form').val();
            var venueFormError = $('#venue-form-error');
            var venueFormSuccess = $('#venue-form-success');

            if (!venue || !handler || !capacity) {
                venueFormError.text('Please fill out all required fields.').removeClass('d-none');
                return;
            }

            venueFormError.text('').addClass('d-none');

            var formData = new FormData(this);

            $.ajax({
                url: "{{ route('admin.store_venue') }}",
                type: 'POST',
                dataType: 'json',
                data: formData,
                contentType: false,
                processData: false,
                success: function(response) {
                    if (response.validate) {
                        venueFormError.text(response.validate).removeClass('d-none');
                    } else {
                        venueFormError.text('').addClass('d-none');
                        
                        venueFormSuccess.text(response.success).removeClass('d-none');
                        $('#create-venue-form')[0].reset();
                        venueTableLoad.ajax.reload();
                        $(".image-container").hide();
                        $('#upload-button-venue').val('');

                        setTimeout(function() {
                            venueFormSuccess.addClass('d-none');
                        }, 5000);
                    }
                },
                error: function(xhr, status, error) {
                    console.error(xhr.responseText);
                }
            });
        });

        
        // entities table
        const entityLoader = $('#entity-loader');
        const entityTable = $('#entities-table');

        entityLoader.addClass('d-none')
        entityTable.removeClass('d-none')
        var entityTableLoad = entityTable.DataTable({
            "scrollCollapse": true,
            "searching": true,
            "paging": true,
            "info": true,
            "lengthChange": true,
            "ordering": true,
            "scrollY": "500px",

            ajax: "{{ url('/admin/get-entites') }}",
            columns: [
                {"data" : "user_id"},
                {
                    "data": null,
                    "render": function(data, type, full, meta) {
                        middlename = '';
                        if (data.middlename !== null) {
                            middlename = data.middlename
                        }
                        return `
                            <td>
                                <div class="d-flex">
                                    <p class="mb-0">${data.lastname},&nbsp;</p>
                                    <p class="mb-0">${data.firstname}&nbsp;</p>
                                    <p class="mb-0">${middlename}</p>
                                </div>
                            </td>
                        `;
                    }
                },
                {"data" : "type"},
                {"data" : "sex"},
                {"data" : "campus"},
                {"data" : "department_code"},
                {
                    "data": null,
                    "render": function(data, type, row, meta) {
                        return `
                            <div class="nav-item dropdown">
                                <a class="btn btn-dark dropdown-toggle" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                    Actions
                                </a>

                                <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                                    <button type="button" data-id="${data.user_id}" class="dropdown-item mb-1 w-100 text-center edit-btn" data-bs-toggle="modal" data-bs-target="#updateEntityModal">Edit Record</button>
                                    <button type="button" data-id="${data.user_id}" class="dropdown-item mb-1 w-100 text-center delete-btn">Delete</button>
                                </div>
                            </div>
                            `;
                    }
                }
            ]
        });

        // create entity
        $('#create-entity-form').submit(function(event) {
            event.preventDefault();

            var userId = $('#user-id').val();
            var firstName = $('#firstname').val();
            var lastName = $('#lastname').val();
            var middleName = $('#middlename').val();
            var entityType = $('#entity-type').val();
            var sex = $('input[name="sex"]:checked').val();
            var department = $('#department').val();

            $('.form-error').text('').addClass('d-none');

            if (!userId) {
                $('#user-id').addClass('is-invalid');
                $('#user-id-error').text('User ID is a required field.').removeClass('d-none');
                return;
            }
            $('#user-id').removeClass('is-invalid');

            if (!firstName || !lastName) {
                $('#firstname').addClass('is-invalid');
                $('#lastname').addClass('is-invalid');
                $('#name-error').text('Both first name and last name are required.').removeClass('d-none');
                return;
            }
            $('#firstname').removeClass('is-invalid');
            $('#lastname').removeClass('is-invalid');

            if (!entityType) {
                $('#entity-type').addClass('is-invalid');
                $('#entity-type-error').text('Type is a required field.').removeClass('d-none');
                return;
            }
            $('#entity-type').removeClass('is-invalid');

            if (!sex) {
                $('#sex-error').text('Please select a sex.').removeClass('d-none');
                return;
            }

            if (!department) {
                $('#department').addClass('is-invalid');
                $('#department-error').text('Department is a required field.').removeClass('d-none');
                return;
            }
            $('#department').removeClass('is-invalid');

            $.ajax({
                type: 'POST',
                url: "{{ route('admin.store_entity') }}",
                data: $('#create-entity-form').serialize(),
                success: function(response) {
                    if (response.success) {
                        entityTableLoad.ajax.reload();
                        $('#showEntityModal').modal('hide');
                        $('#create-entity-form')[0].reset();
                        Swal.fire({
                            icon: 'success',
                            title: 'Success',
                            text: response.success,
                            confirmButtonColor: '#3085d6',
                            confirmButtonText: 'OK'
                        });
                    } else if (response.error){
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: response.error,
                            confirmButtonColor: '#3085d6',
                            confirmButtonText: 'OK'
                        });
                    }
                },
                error: function(xhr, status, error) {
                    console.error(xhr.responseText);
                }
            });
        });

        entityTable.on('click', '.delete-btn', function() {
            Swal.fire({
                title: 'Delete Record',
                text: 'Please note that this action cannot be undone.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#aaa',
                confirmButtonText: 'Delete'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: "{{ url('/delete-entity') }}",
                        type: 'POST',
                        data: {
                            "_token" : "{{ csrf_token() }}",
                            "id" : $(this).data('id')
                        },
                        dataType: 'json',
                        success: function(response) {
                            entityTableLoad.ajax.reload();
                            Swal.fire({
                                icon: 'success',
                                title: 'Deleted',
                                text: 'Record was deleted successfully!',
                                confirmButtonColor: '#3085d6',
                                confirmButtonText: 'OK'
                            });
                        }
                    });
                }
            });
        });

        entityTable.on('click', '.edit-btn', function() {
            $('#update-entity-form')[0].reset();
            $.ajax({
                url: "{{ url('/get-selected-entity') }}",
                type: 'POST',
                data: {
                    "_token" : "{{ csrf_token() }}",
                    "id" : $(this).data('id')
                },
                dataType: 'json',
                success: function(response) {
                    $('#entity_id').val(response.data.user_id);
                    $('#update-user-id').val(response.data.user_id);
                    $('#update-firstname').val(response.data.firstname);
                    $('#update-lastname').val(response.data.lastname);
                    $('#update-middlename').val(response.data.middlename);
                    $('#update-entity-type').val(response.data.type);
                    $('input[name=update_sex][value=' + response.data.sex + ']').prop('checked', true);
                    $('#update-department').val(response.data.department_code);
                }
            });
        });

        $('#update-selected-entity').on('click', function() {
            var firstName = $('#update-firstname').val();
            var lastName = $('#update-lastname').val();
            var middleName = $('#update-middlename').val();
            var entityType = $('#update-entity-type').val();
            var sex = $('input[name="update_sex"]:checked').val();
            var department = $('#update-department').val();

            $('.update-form-error').text('').addClass('d-none');

            if (!firstName || !lastName) {
                $('#update-firstname').addClass('is-invalid');
                $('#update-lastname').addClass('is-invalid');
                $('#update-name-error').text('Both first name and last name are required.').removeClass('d-none');
                return;
            }
            $('#update-firstname').removeClass('is-invalid');
            $('#update-lastname').removeClass('is-invalid');

            if (!entityType) {
                $('#update-entity-type').addClass('is-invalid');
                $('#update-entity-type-error').text('Type is a required field.').removeClass('d-none');
                return;
            }
            $('#update-entity-type').removeClass('is-invalid');

            if (!sex) {
                $('#update-sex-error').text('Please select a sex.').removeClass('d-none');
                return;
            }

            if (!department) {
                $('#update-department').addClass('is-invalid');
                $('#update-department-error').text('Department is a required field.').removeClass('d-none');
                return;
            }
            $('#update-department').removeClass('is-invalid');

            Swal.fire({
                title: 'Update Entity?',
                text: 'Update entity details?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Confirm'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        type: 'POST',
                        url: "{{ url('/update-entity') }}",
                        data: $('#update-entity-form').serialize(),
                        dataType: 'json',
                        success: function(response) {
                            if (response.success){
                                $('#update-entity-form')[0].reset();
                                entityTableLoad.ajax.reload();
                                $('#updateEntityModal').modal('hide');
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Update Complete',
                                    confirmButtonColor: '#3085d6',
                                    confirmButtonText: 'OK'
                                });
                            } else {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Oops!',
                                    text: 'Something went wrong',
                                    confirmButtonColor: '#3085d6',
                                    confirmButtonText: 'OK'
                                });
                            }
                        }
                    });
                }
            });
        });
        
        // departments table
        const departmentLoader = $('#department-loader');
        const departmentTable = $('#departments-table');

        departmentLoader.addClass('d-none')
        departmentTable.removeClass('d-none')
        var departmentTableLoad = departmentTable.DataTable({
            "scrollCollapse": true,
            "searching": true,
            "paging": true,
            "info": true,
            "lengthChange": false,
            "ordering": true,
            "scrollY": "400px",

            ajax: "{{ url('/admin/get-departments') }}",
            columns: [
                {"data" : "department_code"},
                {"data" : "department"},
                {
                    "data": null,
                    "render": function(data, type, row, meta) {
                        return `
                            <div class="nav-item dropdown">
                                <a class="btn btn-dark dropdown-toggle" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                    Actions
                                </a>

                                <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                                    <button type="button" data-id="${data.department_code}" class="dropdown-item mb-1 w-100 text-center edit-btn" data-bs-toggle="modal" data-bs-target="#updateDepartmentModal">Edit Record</button>
                                    <button type="button" data-id="${data.department_code}" class="dropdown-item mb-1 w-100 text-center delete-btn">Delete</button>
                                </div>
                            </div>
                            `;
                    }
                }
            ]
        });

        // create department
        $('#create-department-form').submit(function(event) {
            event.preventDefault();

            var deptCode = $('#dept-code').val();
            var deptName = $('#department-name').val();

            $('.form-error').text('').addClass('d-none');

            if (!deptCode) {
                $('#dept-code').addClass('is-invalid');
                $('#dept-code-error').text('Department code is a required field.').removeClass('d-none');
                return;
            }
            $('#dept-code').removeClass('is-invalid');

            if (!deptName) {
                $('#department-name').addClass('is-invalid');
                $('#department-name-error').text('Department name is a required field.').removeClass('d-none');
                return;
            }
            $('#department-name').removeClass('is-invalid');
            
            $.ajax({
                type: 'POST',
                url: "{{ route('admin.store_department') }}",
                data: $('#create-department-form').serialize(),
                success: function(response) {
                    if (response.success) {
                        departmentTableLoad.ajax.reload();
                        $('#showDepartmentModal').modal('hide');
                        $('#create-department-form')[0].reset();
                        Swal.fire({
                            icon: 'success',
                            title: 'Success',
                            text: response.success,
                            confirmButtonColor: '#3085d6',
                            confirmButtonText: 'OK'
                        });
                    } else if (response.error){
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: response.error,
                            confirmButtonColor: '#3085d6',
                            confirmButtonText: 'OK'
                        });
                    }
                },
                error: function(xhr, status, error) {
                    console.error(xhr.responseText);
                }
            });
        });
        
        departmentTable.on('click', '.delete-btn', function() {
            Swal.fire({
                title: 'Delete Department',
                text: 'Please note that this action cannot be undone.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#aaa',
                confirmButtonText: 'Delete'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: "{{ url('/delete-department') }}",
                        type: 'POST',
                        data: {
                            "_token" : "{{ csrf_token() }}",
                            "id" : $(this).data('id')
                        },
                        dataType: 'json',
                        success: function(response) {
                            departmentTableLoad.ajax.reload();
                            Swal.fire({
                                icon: 'success',
                                title: 'Deleted',
                                text: 'Department was deleted successfully!',
                                confirmButtonColor: '#3085d6',
                                confirmButtonText: 'OK'
                            });
                        }
                    });
                }
            });
        });

        departmentTable.on('click', '.edit-btn', function() {
            $('#update-department-form')[0].reset();
            $.ajax({
                url: "{{ url('/get-selected-department') }}",
                type: 'POST',
                data: {
                    "_token" : "{{ csrf_token() }}",
                    "id" : $(this).data('id')
                },
                dataType: 'json',
                success: function(response) {
                    $('#department_code').val(response.data.department_code);
                    $('#update-dept-code').val(response.data.department_code);
                    $('#update-department-name').val(response.data.department);
                }
            });
        });

        $('#update-selected-department').on('click', function() {
            var deptName = $('#update-department-name').val();

            $('.form-error').text('').addClass('d-none');

            if (!deptName) {
                $('#update-department-name').addClass('is-invalid');
                $('#update-department-name-error').text('Department name is a required field.').removeClass('d-none');
                return;
            }
            $('#update-department-name').removeClass('is-invalid');
            
            Swal.fire({
                title: 'Update Department?',
                text: 'Update department details?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Confirm'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        type: 'POST',
                        url: "{{ url('/update-department') }}",
                        data: $('#update-department-form').serialize(),
                        dataType: 'json',
                        success: function(response) {
                            if (response.success){
                                $('#update-department-form')[0].reset();
                                departmentTableLoad.ajax.reload();
                                $('#updateDepartmentModal').modal('hide');
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Update Complete',
                                    confirmButtonColor: '#3085d6',
                                    confirmButtonText: 'OK'
                                });
                            } else {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Oops!',
                                    text: 'Something went wrong',
                                    confirmButtonColor: '#3085d6',
                                    confirmButtonText: 'OK'
                                });
                            }
                        }
                    });
                }
            });
        });

        // organizations table
        const organizationLoader = $('#organization-loader');
        const organizationTable = $('#organizations-table');

        organizationLoader.addClass('d-none')
        organizationTable.removeClass('d-none')
        var organizationTableLoad = organizationTable.DataTable({
            "scrollCollapse": true,
            "searching": true,
            "paging": true,
            "info": true,
            "lengthChange": false,
            "ordering": true,
            "scrollY": "400px",

            ajax: "{{ url('/admin/get-organizations') }}",
            columns: [
                {"data" : "organization"},
                {"data" : "type"},
                {
                    "data": null,
                    "render": function(data, type, row, meta) {
                        return `
                            <div class="nav-item dropdown">
                                <a class="btn btn-dark dropdown-toggle" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                    Actions
                                </a>

                                <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                                    <button type="button" data-id="${data.id}" class="dropdown-item mb-1 w-100 text-center edit-btn" data-bs-toggle="modal" data-bs-target="#updateOrganizationModal">Edit Record</button>
                                    <button type="button" data-id="${data.id}" class="dropdown-item mb-1 w-100 text-center delete-btn">Delete</button>
                                </div>
                            </div>
                            `;
                    }
                }
            ]
        });

        // create organization
        $('#create-organization-form').submit(function(event) {
            event.preventDefault();

            var orgName = $('#organization-name').val();
            var orgType = $('#organization-type').val();

            $('.form-error').text('').addClass('d-none');

            if (!orgName) {
                $('#organization-name').addClass('is-invalid');
                $('#organization-name-error').text('Organization name is a required field.').removeClass('d-none');
                return;
            }
            $('#organization-name').removeClass('is-invalid');

            if (!orgType) {
                $('#organization-type').addClass('is-invalid');
                $('#organization-type-error').text('Organization type is a required field.').removeClass('d-none');
                return;
            }
            $('#organization-type').removeClass('is-invalid');
            
            $.ajax({
                type: 'POST',
                url: "{{ route('admin.store_organization') }}",
                data: $('#create-organization-form').serialize(),
                success: function(response) {
                    if (response.success) {
                        organizationTableLoad.ajax.reload();
                        $('#showOrganizationModal').modal('hide');
                        $('#create-organization-form')[0].reset();
                        Swal.fire({
                            icon: 'success',
                            title: 'Success',
                            text: response.success,
                            confirmButtonColor: '#3085d6',
                            confirmButtonText: 'OK'
                        });
                    } else if (response.error){
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: response.error,
                            confirmButtonColor: '#3085d6',
                            confirmButtonText: 'OK'
                        });
                    }
                },
                error: function(xhr, status, error) {
                    console.error(xhr.responseText);
                }
            });
        });
        
        organizationTable.on('click', '.delete-btn', function() {
            Swal.fire({
                title: 'Delete Organization',
                text: 'Please note that this action cannot be undone.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#aaa',
                confirmButtonText: 'Delete'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: "{{ url('/delete-organization') }}",
                        type: 'POST',
                        data: {
                            "_token" : "{{ csrf_token() }}",
                            "id" : $(this).data('id')
                        },
                        dataType: 'json',
                        success: function(response) {
                            organizationTableLoad.ajax.reload();
                            Swal.fire({
                                icon: 'success',
                                title: 'Deleted',
                                text: 'Organization was deleted successfully!',
                                confirmButtonColor: '#3085d6',
                                confirmButtonText: 'OK'
                            });
                        }
                    });
                }
            });
        });

        organizationTable.on('click', '.edit-btn', function() {
            $('#update-organization-form')[0].reset();
            $.ajax({
                url: "{{ url('/get-selected-organization') }}",
                type: 'POST',
                data: {
                    "_token" : "{{ csrf_token() }}",
                    "id" : $(this).data('id')
                },
                dataType: 'json',
                success: function(response) {
                    $('#organization_id').val(response.data.id);
                    $('#update-organization-name').val(response.data.organization);
                    $('#update-organization-type').val(response.data.type);
                }
            });
        });

        $('#update-selected-organization').on('click', function() {
            var orgName = $('#update-organization-name').val();
            var orgType = $('#update-organization-type').val();

            $('.form-error').text('').addClass('d-none');

            if (!orgName) {
                $('#update-organization-name').addClass('is-invalid');
                $('#update-organization-name-error').text('Organization name is a required field.').removeClass('d-none');
                return;
            }
            $('#update-organization-name').removeClass('is-invalid');

            if (!orgType) {
                $('#update-organization-type').addClass('is-invalid');
                $('#update-organization-type-error').text('Organization type is a required field.').removeClass('d-none');
                return;
            }
            $('#update-organization-type').removeClass('is-invalid');
            
            Swal.fire({
                title: 'Update Organization?',
                text: 'Update organization details?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Confirm'
            }).then((result) => {
                if (result.isConfirmed) {
                    console.log($('#update-organization-form').serialize());
                    $.ajax({
                        type: 'POST',
                        url: "{{ url('/update-organization') }}",
                        data: $('#update-organization-form').serialize(),
                        dataType: 'json',
                        success: function(response) {
                            if (response.success){
                                $('#update-organization-form')[0].reset();
                                organizationTableLoad.ajax.reload();
                                $('#updateOrganizationModal').modal('hide');
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Update Complete',
                                    confirmButtonColor: '#3085d6',
                                    confirmButtonText: 'OK'
                                });
                            } else {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Oops!',
                                    text: 'Something went wrong',
                                    confirmButtonColor: '#3085d6',
                                    confirmButtonText: 'OK'
                                });
                            }
                        }
                    });
                }
            });
        });
    });
</script>
@endsection