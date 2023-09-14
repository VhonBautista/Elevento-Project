@extends('layouts.app')

@php
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
                <a href="{{ route('admin.dashboard') }}">
                    <i class="fa-solid fa-gauge"></i>
                    <span class="side-link-name">Home</span>
                </a>
            </li>
            <li>
                <a href="{{ route('admin.management') }}" class="active">
                    <i class="fa-solid fa-toolbox"></i>
                    <span class="side-link-name">Management</span>
                </a>
            </li>
            <li>
                <a href="">
                    <i class="fa-solid fa-calendar-days"></i>
                    <span class="side-link-name">Events</span>
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
                    <img src="{{ asset($user->profile_picture) }}" alt="">
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
                <li class="nav-item mx-3">
                    <a href="#" class="btn btn-primary rounded-pill">
                        <i class="fa-solid fa-globe mx-1"></i>
                        Explore Events
                    </a>
                </li>
                <li class="nav-item ">
                    <button type="button" class="btn btn-primary rounded-pill position-relative">
                        <i class="fa-regular fa-bell"></i>
                        <span class="position-absolute notification start-100 translate-middle badge rounded-pill bg-danger">
                            0
                        </span>
                    </button>
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
                    <button class="nav-link manage-tab" id="manage-campus" data-bs-toggle="tab" data-bs-target="#manage-campus-content" type="button" role="tab" aria-controls="manage-campus-content" aria-selected="false">Campus Management</button>
                </li>
                @endif
            </ul>

            <div class="tab-content" id="myTabContent">
                <div class="tab-pane fade p-2 show active" id="manage-user-content" role="tabpanel" aria-labelledby="manage-user-content">
                    @if ( $user->role == "Admin" )
                    <span class="text fw-normal ms-0 liner">Manage Co Administrator</span>
                    <div class="row g-3 mb-4 mt-2 px-3">
                        <div class="col-md-8 mt-0">
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
                                                <th>Sex</th>
                                                <th>Role</th>
                                                <th>Type</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <!-- Permission form modal -->
                        <div class="modal fade" id="permissionModal" tabindex="-1" aria-labelledby="permissionModalLabel" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                            <div class="modal-header bg-primary" style="padding: 6px 18px;">
                                <i class="fa-solid me-2 fa-list-check text-light"></i>
                                <h4 class="text-light m-0 modal-title">Manage Permissions</h4>
                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <form id="update-permission-form" class="row px-2 g-3 pt-2">
                                    @csrf
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

                        <!-- Admin form -->
                        <div class="col-md-4 mt-0">
                            <div class="card">
                                <div class="card-body">
                                    <span class="text-1 fw-normal ms-0">Add New Co-Administrator</span>
                                    <div class="alert alert-danger m-0 d-none alert-box" id="admin-form-error" role="alert"></div>
                                    <div class="alert alert-success m-0 d-none alert-box" id="admin-form-success" role="alert"></div>
                                    <form id="create-admin-form" class="row px-2 g-3 pt-2">
                                        @csrf
                                        <div class="col-12 mt-4">
                                            <label for="user-id-form" class="form-label">User ID</label>
                                            <input type="text" class="form-control" id="user-id-form" placeholder="Enter user ID" name="user_id">
                                        </div>
                                        <div class="col-12 mt-2">
                                            <label for="email-form" class="form-label">Email</label>
                                            <input type="email" class="form-control" id="email-form" placeholder="Enter email address" name="email">
                                        </div>
                                        <div class="col-12 mt-2">
                                            <label for="password-form" class="form-label">Password</label>
                                            <input type="password" class="form-control" id="password-form" placeholder="Enter password" name="password">
                                        </div>
                                        <fieldset class="row mb-0 mt-2">
                                            <legend class="col-form-label col-sm-12 pt-0">Permissions</legend>
                                            <div class="col-sm-6 px-2">
                                                <div class="form-check">
                                                    <input type="checkbox" class="form-check-input" id="manage_user" name="manage_user">
                                                    <label class="small form-check-label" for="manage_user">
                                                    Manage Organizer
                                                    </label>
                                                </div>
                                                <div class="form-check">
                                                    <input type="checkbox" class="form-check-input" id="manage_venue" name="manage_venue">
                                                    <label class="small form-check-label" for="manage_venue">
                                                    Manage Venue
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="col-sm-6 p-0">
                                                <div class="form-check disabled">
                                                    <input type="checkbox" class="form-check-input" id="manage_campus" name="manage_campus">
                                                    <label class="small form-check-label" for="manage_campus">
                                                    Manage Campus
                                                    </label>
                                                </div>
                                                <div class="form-check disabled">
                                                    <input type="checkbox" class="form-check-input" id="manage_event" name="manage_event">
                                                    <label class="small form-check-label" for="manage_event">
                                                    Manage Event
                                                    </label>
                                                </div>
                                            </div>
                                        </fieldset>

                                        <hr class="mt-3">
                                        <div class="col-12 mt-0 p-0">
                                            <button type="submit" class="btn w-100 btn-primary">Add</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif

                    <!-- todo: here -->
                    <!-- @if (($user->role == 'Co-Admin' && $user->manage_user == 1) || $user->role == 'Admin') @endif-->
                    @if (($user->role == 'Co-Admin' && $user->manage_user == 1))
                    <span class="text fw-normal ms-0 liner">Manage Organizer</span>
                    <div class="row g-3 mb-4 mt-2 px-3">
                        <div class="col-md-12 card mt-0">
                            <div class="d-flex justify-content-center align-items-center p-5" id="organizer-loader">
                                <div class="spinner-border text-primary" role="status">
                                    <span class="visually-hidden">Loading...</span>
                                </div>
                            </div>
                            <div class="card-body">
                                <table id="organizers-table" class="d-none">
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
                    @endif

                    <span class="text fw-normal ms-0 liner">Manage Users</span>
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
                                                <th>Status</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <!-- Preview modal -->
                        <div class="modal fade" id="imagePreviewModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
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
                                <h4 class="text-light m-0 modal-title">Manage Venue Details</h4>
                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <form id="update-venue-form" class="row px-2 g-3 pt-2">
                                    @csrf
                                    <input type="hidden" id="selected_venue_id" name="selected_venue_id">
                                    <div class="col-12 mt-2">
                                        <label for="venue-form" class="form-label">Venue Name</label>
                                        <input type="text" class="form-control" id="update_venue" placeholder="Enter venue name" name="update_venue">
                                    </div>
                                    
                                    <p class="mb-0">Venue Photo</p>
                                    <figure class="image-container-update rounded mt-2" style="height: 140px; width: 100%; overflow:hidden; display: none;">
                                        <img id="update_image_venue">
                                    </figure>

                                    <input type="file" id="upload-button-update" id="update-venue-photo-form" name="update_photo_venue" accept="image/*">
                                    <label for="upload-button-update" class="upload-label mt-2">
                                        <i class="fas fa-upload"></i> &nbsp; Upload Photo
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
                                        
                                        <p class="mb-0">Venue Photo</p>
                                        <figure class="image-container rounded mt-2" style="height: 140px; width: 100%; overflow:hidden; display: none;">
                                            <img id="chosen-image-venue">
                                        </figure>

                                        <input type="file" id="upload-button-venue" id="venue-photo-form" name="photo_venue" accept="image/*">
                                        <label for="upload-button-venue" class="upload-label mt-2">
                                            <i class="fas fa-upload"></i> &nbsp; Upload Photo
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
                    <span class="text">Campus Management</span>

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
        
        let lastActiveTab = getCookie("activeTab");
        if (lastActiveTab) {
            $('.manage-tab').removeClass('active');
            $(`#${lastActiveTab}`).addClass('active');
            $('.tab-pane').removeClass('show active');
            $(`#${lastActiveTab}-content`).addClass('show active');
        }

        $('.manage-tab').click(function() {
            let activeTabId = $(this).attr('id');
            setCookie("activeTab", activeTabId, 4);
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
        
        // users table
        const baseUrl = "{{ asset('') }}";
        const userLoader = $('#user-loader');
        const adminLoader = $('#admin-loader');
        const userTable = $('#users-table');
        const adminTable = $('#admins-table');

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
                            return `<span class="text-muted">No organization joined</span>`;
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
                title: 'Activate this user?',
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
                {"data" : "sex"},
                {
                    "data": null,
                    "render": function(data, type, full, meta) {
                        let backgroundColor;
                        switch(data.role) {
                            case 'Admin':
                            case 'Co-Admin':
                                backgroundColor = '#3a6bdd';
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
                {
                    "data": null,
                    "render": function(data, type, full, meta) {
                        return `
                            <td>
                                <button type="button" data-id="${data.id}" class="btn btn-sm btn-dark" data-bs-toggle="modal" data-bs-target="#permissionModal">Manage Permissions</button>
                            </td>
                        `;
                    }
                }
            ]
        });
        
        adminTable.on('click', '.btn-dark', function() {
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

        $('#update-permission-btn').on('click', function() {
            Swal.fire({
                title: 'Update?',
                text: 'Set new permissions to this user?',
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

        $('#create-admin-form').submit(function(event) {
            event.preventDefault();

            var userId = $('#user-id-form').val();
            var email = $('#email-form').val();
            var password = $('#password-form').val();
            var adminFormError = $('#admin-form-error');
            var adminFormSuccess = $('#admin-form-success');

            if (!userId || !email || !password) {
                adminFormError.text('The following fields are required').removeClass('d-none');
                return;
            }
            adminFormError.text('').addClass('d-none');

            $.ajax({
                url: "{{ route('admin.store_admin') }}",
                type: 'POST',
                dataType: 'json',
                data: $('#create-admin-form').serialize(),
                success: function(response) {
                    if (response.validate) {
                        adminFormError.text(response.validate).removeClass('d-none');
                    } else {
                        adminFormError.text('').addClass('d-none');
                        
                        adminFormSuccess.text(response.success).removeClass('d-none');
                        $('#create-admin-form')[0].reset();
                        adminTableLoad.ajax.reload();
                        userTableLoad.ajax.reload();

                        setTimeout(function() {
                            adminFormSuccess.addClass('d-none');
                        }, 5000);
                    }
                },
                error: function(xhr, status, error) {
                    console.error(xhr.responseText);
                }
            });
        });

        // VENUE TAB
        // upload photo
        $("#upload-button-venue").change(function() {
            let reader = new FileReader();
            reader.readAsDataURL(this.files[0]);
            reader.onload = function() {
                $("#chosen-image-venue").attr("src", reader.result);
                $(".image-container").show();
            };
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
                title: 'Activate this venue?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Activate'
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
                title: 'Disable this venue?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Disable'
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
                                text: 'Venue was deleted successfully.',
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
                    $('#update_image_venue').attr('src', response.data.image ? `${baseUrl}${response.data.image}` : `${baseUrl}asset/blank_photo.jpg`);
                    $(".image-container-update").show();
                }
            });
        });

        $('#update-venue-btn').on('click', function() {
            Swal.fire({
                title: 'Update?',
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
            var venueFormError = $('#venue-form-error');
            var venueFormSuccess = $('#venue-form-success');

            if (!venue) {
                venueFormError.text('Venue name is a required field.').removeClass('d-none');
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
    });
</script>
@endsection