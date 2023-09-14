@extends('layouts.app')

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
                <a href="{{ route('profile.edit', ['id' => Auth::user()->id]) }}">
                    <i class="fa-solid fa-gear"></i>
                    <span class="side-link-name">Settings</span>
                </a>
            </li>
        </ul>
        <div class="profile-content">
            <div class="profile">
                <div class="profile-details">
                    <img src="{{ asset(Auth::user()->profile_picture) }}" alt="">
                    <div class="name-role">
                        <div class="user-name">{{ ucfirst(Auth::user()->username) }}</div>
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
                            <img src="@if (Auth::user()->profile_picture == null) {{ asset('asset/blank_profile.jpg') }} @else {{ asset(Auth::user()->profile_picture) }} @endif">
                        </div>
                    </a>
    
                    <ul class="dropdown-menu mt-2" aria-labelledby="navbarDropdown">
                        <li><span class="dropdown-item">{{ ucfirst(Auth::user()->username) }}</span></li>
                        <li><a class="dropdown-item" href="{{ route('profile.edit', ['id' => Auth::user()->id]) }}">Settings</a></li>
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
                    <button class="nav-link active" id="manage-user-tab" data-bs-toggle="tab" data-bs-target="#manage-user" type="button" role="tab" aria-controls="manage-user" aria-selected="true">User Management</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="manage-venue-tab" data-bs-toggle="tab" data-bs-target="#manage-venue" type="button" role="tab" aria-controls="manage-venue" aria-selected="false">Venue Management</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="manage-campus-tab" data-bs-toggle="tab" data-bs-target="#manage-campus" type="button" role="tab" aria-controls="manage-campus" aria-selected="false">Campus Management</button>
                </li>
            </ul>

            <div class="tab-content" id="myTabContent">
                <div class="tab-pane fade p-2 show active" id="manage-user" role="tabpanel" aria-labelledby="manage-user-tab">
                    <span class="text-1 ms-0 liner">User Management</span>
                    <div class="row g-3 mb-5 mt-2 px-3">
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
                                            <th class="user-data-event-profile">Profile</th>
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

                    @if ( Auth::user()->role == "Admin" )
                    <span class="text-1 ms-0 liner">Co Administrator</span>
                    <div class="row g-3 mt-2 px-3">
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
                                                <th class="user-data-event-profile">Profile</th>
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

                        <!-- create admin form -->
                        <div class="col-md-4 mt-0">
                            <div class="card">
                                <div class="card-body">
                                    <span class="text-1 fw-normal ms-0">Create Co-Administrator</span>
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
                                        <div class="col-12 mt-5">
                                            <button type="submit" class="btn w-100 btn-primary">Create</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif
                </div>

                <div class="tab-pane fade p-2" id="manage-venue" role="tabpanel" aria-labelledby="manage-venue-tab">
                    <span class="text">Venue Management</span>

                </div>

                <div class="tab-pane fade p-2" id="manage-campus" role="tabpanel" aria-labelledby="manage-campus-tab">
                    <span class="text">Campus Management</span>

                </div>
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
                                <div class="row justify-content-center user-data-event-profile">
                                    <div class="col-md-3 text-center">
                                        <div style="width: 45px; height: 45px; overflow: hidden; border-radius: 50%; margin: 0 auto;">
                                            <img src="${baseUrl}${data.profile_picture ? data.profile_picture : 'asset/blank_profile.jpg'}" style="width: 100%; height: 100%; object-fit: cover;">
                                        </div>
                                    </div>

                                    <div class="col-md-8 ms-2 text-start">
                                        <p class="m-0">${data.username}</p>
                                        <p class="small text-muted m-0">${data.email}</p>
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
                                backgroundColor = '#1bb835';
                                break;
                            default:
                                backgroundColor = '#e09e11';
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
                                backgroundColor = '#e09e11';
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
                            return '<span class="text-muted">No organization joined</span>';
                        }
                    }
                },
                {
                    "data": null,
                    "render": function(data, type, row, meta) {
                        if (data.isDisabled) {
                            return '<button type="button" data-id="' + data.id + '" class="btn btn-success">Activate</button>';
                        } else {
                            return '<button type="button" data-id="' + data.id + '" class="btn btn-danger">Disable</button>';
                        }
                    }
                }
            ]
        });

        userTable.on('click', '.btn-success', function() {
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
                                <div class="row justify-content-center user-data-event-profile">
                                    <div class="col-md-3 text-center">
                                        <div style="width: 45px; height: 45px; overflow: hidden; border-radius: 50%; margin: 0 auto;">
                                            <img src="${baseUrl}${data.profile_picture ? data.profile_picture : 'asset/blank_profile.jpg'}" style="width: 100%; height: 100%; object-fit: cover;">
                                        </div>
                                    </div>

                                    <div class="col-md-8 ms-2 text-start">
                                        <p class="m-0">${data.username}</p>
                                        <p class="small text-muted m-0">${data.email}</p>
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
                                backgroundColor = '#3a6bdd';
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
                                backgroundColor = '#e09e11';
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
                                <div class="nav-item dropdown">
                                    <a class="btn btn-dark btn-sm dropdown-toggle" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                        Access Type
                                    </a>
                                    <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                                        <a class="dropdown-item mb-1 make-admin" href="${data.id}">Allow User Management</a>
                                        <a class="dropdown-item mb-1 make-admin" href="${data.id}">Allow Venue Management</a>
                                        <a class="dropdown-item mb-1 make-admin" href="${data.id}">Allow Campus Management</a>
                                        <a class="dropdown-item mb-1 make-admin" href="${data.id}">Allow Event Management</a>
                                    </div>
                                </div>
                            </td>
                        `;
                    }
                }
            ]
        });
        
        $('#create-admin-form').submit(function(event) {
            event.preventDefault();

            var userId = $('#user-id-form').val();
            var email = $('#email-form').val();
            var password = $('#password-form').val();
            var adminFormError = $('#admin-form-error');
            var adminFormSuccess = $('#admin-form-success');

            if (!userId || !email || !password) {
                return;
            }
            adminFormError.text('').addClass('d-none');

            $.ajax({
                url: "{{ route('admin.store_admin') }}",
                type: 'POST',
                dataType: 'json',
                data: $('#create-admin-form').serialize(),
                success: function(response) {
                    if (response.errorRegister) {
                        adminFormError.text(response.errorRegister).removeClass('d-none');
                    } else {
                        if (response.errorRegister) {
                            adminFormError.text(response.errorRegister).removeClass('d-none');
                        }
                        adminFormError.text('').addClass('d-none');
                        
                        adminFormSuccess.text(response.successRegister).removeClass('d-none');
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
    });
</script>
@endsection