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
                <a href="">
                    <i class="fa-solid fa-calendar-days" class="active"></i>
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
                <a href="{{ route('admin.management') }}">
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
            <ul class="nav mt-2" style="height: 20px !important;">
                <li class="nav-item">
                    <a class="nav-link p-0" href="#approvals">Event Approvals</a>
                </li>
                <li class="nav-item">
                    <p class="nav-link text-dark px-2 py-0">|</p>
                </li>
                <li class="nav-item">
                    <a class="nav-link p-0" href="#rejects">Rejected Events</a>
                </li>
            </ul>

            <span class="text fw-normal ms-0 liner" id="approvals">Event Approvals</span>
            <div class="row g-3 mb-4 mt-2 px-3" >
                <div class="col-md-9 mt-0 mb-3">
                    <div class="card">
                        <div class="d-flex justify-content-center align-items-center p-5" id="event-loader">
                            <div class="spinner-border text-primary" role="status">
                                <span class="visually-hidden">Loading...</span>
                            </div>
                        </div>
                        <div class="card-body">
                            <table id="events-table" class="d-none">
                                <thead style="overflow-x: scroll !important;">
                                    <tr>
                                        <th>Title</th>
                                        <th>Event Type</th>
                                        <th>Start</th>
                                        <th>End</th>
                                        <th>Venue</th>
                                        <th>Audience</th>
                                        <th>Requested By</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="col-md-3 mt-0 mb-3">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0">Event Report</h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-10 card mx-auto py-4 mb-3">
                                    <canvas id="eventCountsChart" style="width: 100%; height: 200px;"></canvas>
                                </div>
                                <div class="col-md-12">
                                    <div class="card bg-primary text-white mb-3">
                                        <div class="card-body">For Approval: <span id="pending-events-count">{{ $pendingEventsCount }}</span></div>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="card text-white mb-3" style="background-color: #222;">
                                        <div class="card-body">Planning Phase: <span id="planning-events-count">{{ $planningEventsCount }}</span></div>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="card bg-success text-white mb-3">
                                        <div class="card-body">Active: <span id="active-events-count">{{ $activeEventsCount }}</span></div>
                                    </div>
                                </div>
                                <hr>
                                <div class="col-md-12">
                                    <div class="card bg-danger text-white">
                                        <div class="card-body">Rejected: <span id="rejected-events-count">{{ $rejectedEventsCount }}</span></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <span class="text fw-normal ms-0 liner" id="rejects">Rejected Events</span>
            <div class="row g-3 mb-4 mt-2 px-3">
                <div class="col-md-12 mt-0 mb-3">
                    <div class="card">
                        <div class="d-flex justify-content-center align-items-center p-5" id="rejected-loader">
                            <div class="spinner-border text-primary" role="status">
                                <span class="visually-hidden">Loading...</span>
                            </div>
                        </div>
                        <div class="card-body">
                            <table id="rejected-events-table" class="d-none">
                                <thead style="overflow-x: scroll !important;">
                                    <tr>
                                        <th>Title</th>
                                        <th>Event Type</th>
                                        <th>Start</th>
                                        <th>End</th>
                                        <th>Venue</th>
                                        <th>Audience</th>
                                        <th>Requested By</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
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

        // chart
        function updateChart() {
            $.ajax({
                url: "{{ route('getUpdatedEventCounts') }}",
                method: 'GET',
                success: function(response) {
                    const data = {
                        labels: ['Active', 'For Approval', 'Rejected', 'Planning Phase'],
                        datasets: [{
                            data: [
                                response.activeEventsCount,
                                response.pendingEventsCount,
                                response.rejectedEventsCount,
                                response.planningEventsCount
                            ],
                            backgroundColor: [
                                'rgba(255, 99, 132, 0.6)',
                                'rgba(54, 162, 235, 0.6)',
                                'rgba(255, 206, 86, 0.6)',
                                'rgba(75, 192, 192, 0.6)'
                            ],
                            borderColor: [
                                'rgba(255, 99, 132, 1)',
                                'rgba(54, 162, 235, 1)',
                                'rgba(255, 206, 86, 1)',
                                'rgba(75, 192, 192, 1)'
                            ],
                            borderWidth: 1
                        }]
                    };

                    const ctx = $('#eventCountsChart')[0].getContext('2d');
                    
                    if (window.chartInstance) {
                        window.chartInstance.data = data;
                        window.chartInstance.update();
                    } else {
                        window.chartInstance = new Chart(ctx, {
                            type: 'doughnut',
                            data: data,
                            options: options
                        });
                    }
                },
                error: function(error) {
                    console.error(error);
                }
            });
        }

        const options = {
            responsive: true,
            maintainAspectRatio: false
        };

        updateChart();

        // loader
        const eventLoader = $('#event-loader');
        const rejectedLoader = $('#rejected-loader');
        
        // table
        const eventTable = $('#events-table');
        const rejectedEventTable = $('#rejected-events-table');

        // events table
        eventLoader.addClass('d-none')
        eventTable.removeClass('d-none')
        var eventTableLoad = eventTable.DataTable({
            "scrollCollapse": true,
            "searching": true,
            "paging": true,
            "info": true,
            "lengthChange": true,
            "ordering": false,
            "scrollY": "600px",

            ajax: "{{ url('/admin/get-events-approval') }}",
            columns: [
                {"data" : "title"},
                {"data" : "event_type"},
                {
                    "data" : "start_date",
                    "render": function(data, type, row) {
                        const date = new Date(data);
                        return date.toLocaleDateString('en-US', { year: 'numeric', month: 'long', day: 'numeric' });
                    }
                },
                {
                    "data" : "end_date",
                    "render": function(data, type, row) {
                        const endDate = new Date(data);
                        const startDate = new Date(row.start_date);

                        if (startDate.getDate() === endDate.getDate() - 1 &&
                            startDate.getMonth() === endDate.getMonth() &&
                            startDate.getFullYear() === endDate.getFullYear()) {
                            
                            endDate.setDate(endDate.getDate() - 1);
                        }

                        return endDate.toLocaleDateString('en-US', { year: 'numeric', month: 'long', day: 'numeric' });
                    }
                },
                {"data" : "venue_name"},
                {"data" : "target_audience"},
                {"data" : "username"},
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
                                    <button type="button" data-id="${data.id}" class="btn btn-success w-100">Accept</button>
                                </div>
                                <div class="dropdown-divider"></div>
                                <div class="dropdown-item">
                                    <button type="button" data-id="${data.id}" class="btn btn-danger w-100">Reject</button>
                                </div>
                            </div>
                        </div>
                        `;
                    }
                }
            ]
        });
        
        eventTable.on('click', '.btn-success', function() {
            Swal.fire({
                title: 'Accept Event Request?',
                text: 'Accepting this request implies that all required documents and paperwork have been physically provided and approved.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#aaa',
                confirmButtonText: 'Yes'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: "{{ url('/accept-event') }}",
                        type: 'POST',
                        data: {
                            "_token" : "{{ csrf_token() }}",
                            "id" : $(this).data('id')
                        },
                        dataType: 'json',
                        success: function(response) {
                            $('#pending-events-count').text(response.pendingEventsCount);
                            $('#active-events-count').text(response.activeEventsCount);
                            $('#rejected-events-count').text(response.rejectedEventsCount);
                            $('#planning-events-count').text(response.planningEventsCount);

                            eventTableLoad.ajax.reload();
                            updateChart();
                            Swal.fire({
                                icon: 'success',
                                title: 'Success',
                                text: 'Event has been accepted successfully.',
                                confirmButtonColor: '#3085d6',
                                confirmButtonText: 'OK'
                            });
                        },
                        error: function(xhr, status, error) {
                            console.error(xhr.responseText);
                        }
                    });
                }
            });
        });
        
        eventTable.on('click', '.btn-danger', function() {
            Swal.fire({
                title: 'Reject Event Request?',
                text: 'Rejecting this request implies that all required documents and paperwork have not been physically provided and approved.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#aaa',
                confirmButtonText: 'Yes'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: "{{ url('/reject-event') }}",
                        type: 'POST',
                        data: {
                            "_token" : "{{ csrf_token() }}",
                            "id" : $(this).data('id')
                        },
                        dataType: 'json',
                        success: function(response) {
                            $('#pending-events-count').text(response.pendingEventsCount);
                            $('#active-events-count').text(response.activeEventsCount);
                            $('#rejected-events-count').text(response.rejectedEventsCount);
                            $('#planning-events-count').text(response.planningEventsCount);

                            eventTableLoad.ajax.reload();
                            rejectedEventTableLoad.ajax.reload();
                            updateChart();
                            Swal.fire({
                                icon: 'success',
                                title: 'Success',
                                text: 'Event has been rejected.',
                                confirmButtonColor: '#3085d6',
                                confirmButtonText: 'OK'
                            });
                        },
                        error: function(xhr, status, error) {
                            console.error(xhr.responseText);
                        }
                    });
                }
            });
        });

        // rejected table
        rejectedLoader.addClass('d-none')
        rejectedEventTable.removeClass('d-none')
        var rejectedEventTableLoad = rejectedEventTable.DataTable({
            "scrollCollapse": true,
            "searching": true,
            "paging": true,
            "info": true,
            "lengthChange": true,
            "ordering": true,
            "scrollY": "600px",

            ajax: "{{ url('/admin/get-events-rejected') }}",
            columns: [
                {"data" : "title"},
                {"data" : "event_type"},
                {
                    "data" : "start_date",
                    "render": function(data, type, row) {
                        const date = new Date(data);
                        return date.toLocaleDateString('en-US', { year: 'numeric', month: 'long', day: 'numeric' });
                    }
                },
                {
                    "data" : "end_date",
                    "render": function(data, type, row) {
                        const endDate = new Date(data);
                        const startDate = new Date(row.start_date);

                        if (startDate.getDate() === endDate.getDate() - 1 &&
                            startDate.getMonth() === endDate.getMonth() &&
                            startDate.getFullYear() === endDate.getFullYear()) {
                            
                            endDate.setDate(endDate.getDate() - 1);
                        }

                        return endDate.toLocaleDateString('en-US', { year: 'numeric', month: 'long', day: 'numeric' });
                    }
                },
                {"data" : "venue_name"},
                {"data" : "target_audience"},
                {"data" : "username"},
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
                                    <button type="button" data-id="${data.id}" class="btn btn-success w-100">Accept</button>
                                </div>
                                <div class="dropdown-divider"></div>
                                <div class="dropdown-item">
                                    <button type="button" data-id="${data.id}" class="btn btn-danger w-100">Delete</button>
                                </div>
                            </div>
                        </div>
                        `;
                    }
                }
            ]
        });

        rejectedEventTable.on('click', '.btn-success', function() {
            Swal.fire({
                title: 'Accept Event Request?',
                text: 'Accepting this request implies that all required documents and paperwork have been physically provided and approved.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#aaa',
                confirmButtonText: 'Yes'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: "{{ url('/accept-event') }}",
                        type: 'POST',
                        data: {
                            "_token" : "{{ csrf_token() }}",
                            "id" : $(this).data('id')
                        },
                        dataType: 'json',
                        success: function(response) {
                            $('#pending-events-count').text(response.pendingEventsCount);
                            $('#active-events-count').text(response.activeEventsCount);
                            $('#rejected-events-count').text(response.rejectedEventsCount);
                            $('#planning-events-count').text(response.planningEventsCount);

                            rejectedEventTableLoad.ajax.reload();
                            updateChart();
                            Swal.fire({
                                icon: 'success',
                                title: 'Success',
                                text: 'Event has been accepted successfully.',
                                confirmButtonColor: '#3085d6',
                                confirmButtonText: 'OK'
                            });
                        },
                        error: function(xhr, status, error) {
                            console.error(xhr.responseText);
                        }
                    });
                }
            });
        });

        rejectedEventTable.on('click', '.btn-danger', function() {
            Swal.fire({
                title: 'Delete Event Request',
                text: 'Please note that this action cannot be undone.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#aaa',
                confirmButtonText: 'Yes'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: "{{ url('/destroy-event') }}",
                        type: 'POST',
                        data: {
                            "_token" : "{{ csrf_token() }}",
                            "id" : $(this).data('id')
                        },
                        dataType: 'json',
                        success: function(response) {
                            $('#pending-events-count').text(response.pendingEventsCount);
                            $('#active-events-count').text(response.activeEventsCount);
                            $('#rejected-events-count').text(response.rejectedEventsCount);
                            $('#planning-events-count').text(response.planningEventsCount);

                            rejectedEventTableLoad.ajax.reload();
                            updateChart();
                            Swal.fire({
                                icon: 'success',
                                title: 'Success',
                                text: 'Event has been deleted permanently.',
                                confirmButtonColor: '#3085d6',
                                confirmButtonText: 'OK'
                            });
                        },
                        error: function(xhr, status, error) {
                            console.error(xhr.responseText);
                        }
                    });
                }
            });
        });
    });
</script>
@endsection