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
                <a href="{{ route('admin.dashboard') }}" class="active">
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
                    <i class="fa-solid fa-calendar-days"></i>
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
                <li class="nav-item me-2">
                    <button type="button" id="create-event-btn" class="btn px-4 btn-primary rounded-pill mx-1">
                        <i class="fa-solid me-2 fa-calendar-plus"></i>
                        Create Event
                    </button>
                    <a href="#" class="btn btn-primary px-4 rounded-pill mx-1">
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
                                        <div class="me-4">
                                            <!-- icon -->
                                            icon
                                        </div>
                                        <div class="w-100">
                                            <div class="d-flex justify-content-between align-items-center">
                                                <p class="fw-bold m-0" style="font-size: 16px">{{ $notification->data['title'] }}</p>
                                                <p class="fw-bold small m-0">{{ Carbon::parse($notification->created_at)->format('h:i A') }}</p>
                                            </div>
                                            <div class="row">
                                                <div class="col">
                                                    <p class="text-secondary small m-0">{{ $notification->data['message'] }}</p>
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
                    <button class="nav-link dash-tab active" id="dashboard-tab" data-bs-toggle="tab" data-bs-target="#dashboard-tab-content" type="button" role="tab" aria-controls="dashboard-tab-content" aria-selected="true">Dashboard</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link dash-tab" id="analytics-tab" data-bs-toggle="tab" data-bs-target="#analytics-tab-content" type="button" role="tab" aria-controls="analytics-tab-content" aria-selected="false">Analytics</button>
                </li>
            </ul>

            <div class="tab-content" id="myTabContent">
                <div class="tab-pane fade p-2 show active" id="dashboard-tab-content" role="tabpanel" aria-labelledby="dashboard-tab-content">
                    <div class="row flex-lg-nowrap mt-2">
                        <div class="col">
                            <div class="row">
                                <div class="col-md-8 mb-3">
                                    @if ($user->role == 'Admin')
                                    <span class="m-0 text fw-normal">{{ __('Happening Today') }}</span>
                                    <div id="carouselExampleCaptions" class="carousel mt-2 mb-4 slide" data-bs-ride="carousel">
                                        <div class="carousel-indicators">
                                            <button type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Slide 1"></button>
                                            <button type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide-to="1" aria-label="Slide 2"></button>
                                            <button type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide-to="2" aria-label="Slide 3"></button>
                                        </div>
                                        <div class="carousel-inner rounded" style="max-height: 250px;">
                                            <div class="carousel-item active">
                                                <img src="{{ asset('asset/flip_front.png') }}" class="d-block w-100" alt="...">
                                            </div>
                                            <div class="carousel-item">
                                                <img src="{{ asset('asset/flip_front.png') }}" class="d-block w-100" alt="...">
                                            </div>
                                            <div class="carousel-item">
                                                <img src="{{ asset('asset/flip_front.png') }}" class="d-block w-100" alt="...">
                                            </div>
                                        </div>
                                        <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide="prev">
                                            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                            <span class="visually-hidden">Previous</span>
                                        </button>
                                        <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide="next">
                                            <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                            <span class="visually-hidden">Next</span>
                                        </button>
                                    </div>
                                    @endif

                                    <div class="card p-2">
                                        <div class="d-flex justify-content-center align-items-center p-5" id="calendar-event-loader">
                                            <div class="spinner-border text-primary" role="status">
                                                <span class="visually-hidden">Loading...</span>
                                            </div>
                                        </div>
                                        <div class="card-body">
                                            <div id="calendar" class="d-none"></div>
                                        </div>
                                    </div>

                                    <!-- Calendar modal -->
                                    <div class="modal fade" id="calendarModal" tabindex="-1" aria-labelledby="calendarModalLabel" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header bg-primary" style="padding: 6px 18px;">
                                                    <i class="fa-solid me-2 fa-calendar-days text-light"></i>
                                                    <h5 class="text-light m-0 modal-title">Create Event</h5>
                                                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <form id="create-event-form" class="row px-2 g-3 pt-2">
                                                    @csrf
                                                    
                                                        <div class="col-md-12">
                                                            <label for="event-title" class="form-label">Title</label>
                                                            <input type="text" class="form-control" id="event-title" placeholder="Enter event title" name="event_title">
                                                            <div id="event-title-error" class="form-error mt-2 text-danger small d-none"></div>
                                                        </div>

                                                        <div class="col-md-6">
                                                            <label for="event-start" class="form-label">Start</label>
                                                            <input type="date" class="form-control" id="event-start" name="event_start">
                                                            <div id="event-start-error" class="form-error mt-2 text-danger small d-none"></div>
                                                        </div>

                                                        <div class="col-md-6">
                                                            <label for="event-end" class="form-label">End</label>
                                                            <input type="date" class="form-control" id="event-end" name="event_end">
                                                            <div id="event-end-error" class="form-error mt-2 text-danger small d-none"></div>
                                                        </div>

                                                        <div class="col-md-12">
                                                            <small class="form-text text-muted">Note: Please set the end date to be at least one day after the actual end date of the event. For example, if the event starts on July 20, set the end date to July 21 or later to ensure accurate scheduling.</small>
                                                        </div>

                                                        <div class="col-md-12">
                                                            <hr class="m-0 mb-2">
                                                            <label for="event-type" class="form-label">Type</label>
                                                            <select class="form-select" id="event-type" name="entity_type">
                                                                <option value="" selected>Select Type</option>
                                                                @foreach ($eventTypes as $eventType)
                                                                <option value="{{ $eventType->event_type }}"> {{ $eventType->event_type }}</option>
                                                                @endforeach
                                                            </select>
                                                            <div id="event-type-error" class="form-error mt-2 text-danger small d-none"></div>
                                                        </div>

                                                        <div class="col-md-12">
                                                            <label for="target-audience" class="form-label">Target Audience</label>
                                                            <select class="form-select" id="target-audience" name="target_audience">
                                                                <option value="all" selected>All</option>
                                                                <option value="student">Student</option>
                                                                <option value="faculty">Faculty</option>
                                                            </select>
                                                            <div id="target-audience-error" class="form-error mt-2 text-danger small d-none"></div>
                                                        </div>

                                                        <div class="col-md-12">
                                                            <label for="venue" class="form-label">Venue</label>
                                                            <select class="form-select" id="campus" name="campus">
                                                                <option value="" selected>Select Campus</option>
                                                                @foreach ($campuses as $campus)
                                                                <option value="{{ $campus->campus }}"> {{ $campus->campus }}</option>
                                                                @endforeach
                                                            </select>

                                                            <select class="form-select mt-3 d-none" id="venue" name="venue"></select>
                                                            
                                                            <div id="venue-error" class="form-error mt-2 text-danger small d-none"></div>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                                        <button type="submit" class="btn btn-primary">Create Event</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                            
                                </div>

                                <div class="col-md-4">

                                    <span class="m-0 text fw-normal">{{ __('Weather Today') }}</span>
                                    <div class="weather-data mb-0 mt-2">
                                        <div class="current-weather mb-2" style="box-shadow: 3px 3px 4px #888888;">
                                            <div class="details" style="width: 100%;">
                                                <h5 class="card-title placeholder-glow">
                                                    <span class="placeholder col-6"></span>
                                                </h5>
                                                <p class="card-text placeholder-glow">
                                                    <span class="placeholder col-7"></span>
                                                    <span class="placeholder col-4"></span>
                                                    <span class="placeholder col-4"></span>
                                                    <span class="placeholder col-6"></span>
                                                    <span class="placeholder col-8"></span>
                                                </p>    
                                            </div>
                                            <div class="icon" style="width: 100%;">
                                                <div class="spinner-border mt-3" role="status">
                                                    <span class="visually-hidden">Loading...</span>
                                                </div>
                                                <p class="card-text placeholder-glow">
                                                    <span class="placeholder col-7"></span>
                                                </p> 
                                            </div>
                                        </div>
                                        <div class="alert alert-danger d-none" role="alert" id="weather-error">
                                            <div id="weather-message"></div>
                                        </div>
                                        <div class="input-group mb-3 mt-3">
                                            <input type="text" class="form-control city-input" placeholder="Search for a city" value="{{ session('campus') }}" aria-describedby="button-addon2">
                                            <button class="btn search-btn btn-primary text-light" type="button" id="button-addon2">Search</button>
                                        </div>
                                        <div class="days-forecast">
                                            <span class="m-0 text-1 fw-normal">{{ __('5 Day Forecast') }}</span>
                                            <div class="weather-cards p-0 mt-2">
                                                <div class="items" style="width: 100%;">
                                                    <div class="spinner-border" role="status">
                                                        <span class="visually-hidden">Loading...</span>
                                                    </div>
                                                </div>
                                                <div class="items" style="width: 100%;">
                                                    <div class="spinner-border" role="status">
                                                        <span class="visually-hidden">Loading...</span>
                                                    </div>
                                                </div>
                                                <div class="items" style="width: 100%;">
                                                    <div class="spinner-border" role="status">
                                                        <span class="visually-hidden">Loading...</span>
                                                    </div>
                                                </div>
                                                <div class="items" style="width: 100%;">
                                                    <div class="spinner-border" role="status">
                                                        <span class="visually-hidden">Loading...</span>
                                                    </div>
                                                </div>
                                                <div class="items" style="width: 100%;">
                                                    <div class="spinner-border" role="status">
                                                        <span class="visually-hidden">Loading...</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    @if (($user->role == 'Co-Admin' && $user->manage_event == 1) || $user->role == 'Admin')
                                    <span class="m-0 text-1 fw-normal">{{ __('Events Awaiting Approval') }}</span>
                                    <div class="d-flex justify-content-center align-items-center p-5" id="pending-event-loader">
                                        <div class="spinner-border text-primary" role="status">
                                            <span class="visually-hidden">Loading...</span>
                                        </div>
                                    </div>
                                    <div class="table-overflow p-2">
                                        <table id="pending-event-table" class="dashboard-pending-table d-none">
                                            <thead class="d-none">
                                                <tr>
                                                    <th></th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($events as $event)
                                                <tr>
                                                    <td>
                                                        <div class="card p-1 mb-2">
                                                            <div class="card-body py-1 d-flex justify-content-between align-items-center">
                                                                <div class="container">
                                                                    <div class="row pending-date">
                                                                    &#9679; {{ \Carbon\Carbon::parse($event->start_date)->format('F j') }} - {{ \Carbon\Carbon::parse($event->end_date)->format('F j') }}
                                                                    </div>
                                                                    <div class="row pending-title">{{ $event->title }}</div>
                                                                    <div class="row pending-type">{{ $event->event_type }}</div>
                                                                </div>
                                                                <a href="{{ route('admin.approval') }}" class="btn btn-primary py-0">Manage</a>
                                                            </div>
                                                        </div>
                                                    </td>
                                                </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                    @endif
                                </div>

                            </div>
                        </div>
                    </div>
                </div>

                <div class="tab-pane fade p-2" id="analytics-tab-content" role="tabpanel" aria-labelledby="analytics-tab-content">
                    <span class="text">Analytics</span>

                    <!-- 4 cards -->
                    <div class="row pt-2">
                        <div class="col-xl-3 col-md-6">
                            <div class="card bg-primary text-white mb-4">
                                <div class="card-body">Primary Card</div>
                                <div class="card-footer d-flex align-items-center justify-content-between">
                                    <a class="small text-white stretched-link" href="#">View Details</a>
                                    <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-3 col-md-6">
                            <div class="card bg-success text-white mb-4">
                                <div class="card-body">Success Card</div>
                                <div class="card-footer d-flex align-items-center justify-content-between">
                                    <a class="small text-white stretched-link" href="#">View Details</a>
                                    <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-3 col-md-6">
                            <div class="card bg-danger text-white mb-4">
                                <div class="card-body">Danger Card</div>
                                <div class="card-footer d-flex align-items-center justify-content-between">
                                    <a class="small text-white stretched-link" href="#">View Details</a>
                                    <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- graphs -->
                    <div class="row mt-2 g-4">
                        <div class="col-xl-8">
                            <div class="card mb-4">
                                <div class="card-header">
                                    <i class="fas fa-chart-area me-1"></i>
                                    Area Chart Example
                                </div>
                                <div class="card-body">
                                    <canvas id="lineChart"></canvas>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-4">
                            <div class="card row mb-3">
                                <div class="card-header">
                                    <i class="fas fa-chart-bar me-1"></i>
                                    Bar Chart Example
                                </div>
                                <div class="card-body">
                                    <canvas id="barChart"></canvas>    
                                </div>
                            </div>
                            <div class="card row">
                                <div class="card-header">
                                    <i class="fas fa-chart-bar me-1"></i>
                                    Bar Chart Example
                                </div>
                                <div class="card-body">
                                    <canvas id="barChart"></canvas>    
                                </div>
                            </div>
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
  const lineCtx = document.getElementById('lineChart');
  const barCtx = document.getElementById('barChart');

  new Chart(lineCtx, {
    type: 'line',
    data: {
      labels: ['Red', 'Blue', 'Yellow', 'Green', 'Purple', 'Orange'],
      datasets: [{
        label: '# of Votes',
        data: [12, 19, 3, 5, 2, 3],
        borderWidth: 1
      }]
    },
    options: {
        maintainAspectRatio: false,
        scales: {
        y: {
          beginAtZero: true
        }
      }
    }
  });

  new Chart(barCtx, {
    type: 'bar',
    data: {
      labels: ['Red', 'Blue', 'Yellow', 'Green', 'Purple', 'Orange'],
      datasets: [{
        label: '# of Votes',
        data: [12, 19, 3, 5, 2, 3],
        borderWidth: 1
      }]
    },
    options: {
        maintainAspectRatio: false,
      scales: {
        y: {
          beginAtZero: true
        }
      }
    }
  });
</script>
<script>
    $(document).ready(function() {
        let menubtn = $("#menu-btn");
        let sidebar = $(".sidebar");
        
        let isSidebarActive = getCookie("sidebarActive") === "true";
        if (isSidebarActive) {
            sidebar.addClass("active-sidebar");
        }

        let lastActiveTab = getCookie("activeTabDash");
        if (lastActiveTab) {
            $('.dash-tab').removeClass('active');
            $(`#${lastActiveTab}`).addClass('active');
            $('.tab-pane').removeClass('show active');
            $(`#${lastActiveTab}-content`).addClass('show active');
        }

        $('.dash-tab').click(function() {
            let activeTabId = $(this).attr('id');
            setCookie("activeTabDash", activeTabId, 4);
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

        // loader
        const pendingLoader = $('#pending-event-loader');
        const pendingTable = $('#pending-event-table');
        const calendarLoader = $('#calendar-event-loader');
        const calendarEvent = $('#calendar');

        pendingLoader.addClass('d-none')
        calendarLoader.addClass('d-none')
        pendingTable.removeClass('d-none')
        calendarEvent.removeClass('d-none')

        // weather app
        const cityInput = $(".city-input");
        const currentWeatherDiv = $(".current-weather");
        const weatherCardsDiv = $(".weather-cards");
        const API_KEY = "bb40f2978c3d25a90dcfacf68a1206ec";

        const createWeatherCard = (cityName, weatherItem, index) => {
            const dateString = weatherItem.dt_txt.split(" ")[0];
            const date = new Date(dateString);
            const monthNames = ["Jan", "Feb", "Mar", "Apr", "May", "Jun",
                "Jul", "Aug", "Sept", "Oct", "Nov", "Dec"];
            
            const formattedDate = monthNames[date.getMonth()] + ' ' + date.getDate() + ', ' + date.getFullYear();
            
            if(index === 0) { 
                return `
                <div class="details">
                    <h2 class='mb-3'>${cityName}, ${formattedDate}</h2>
                    <h6>Temperature: ${(weatherItem.main.temp - 273.15).toFixed(2)}°C</h6>
                    <h6>Wind: ${weatherItem.wind.speed} M/S</h6>
                    <h6>Humidity: ${weatherItem.main.humidity}%</h6>
                </div>
                <div class="icon">
                    <img src="https://openweathermap.org/img/wn/${weatherItem.weather[0].icon}@4x.png" alt="weather-icon">
                    <h6>${weatherItem.weather[0].description}</h6>
                </div>
                `;
            } else {
                return `
                <div class="items mb-2" style="box-shadow: 3px 3px 4px #888888;">
                    <h3>${formattedDate}</h3>
                    <img src="https://openweathermap.org/img/wn/${weatherItem.weather[0].icon}@4x.png" alt="weather-icon">
                    <h5>${weatherItem.weather[0].description}</h5>
                    <h6>Temp: ${(weatherItem.main.temp - 273.15).toFixed(2)}°C</h6>
                    <h6>Wind: ${weatherItem.wind.speed} M/S</h6>
                    <h6>Humidity: ${weatherItem.main.humidity}%</h6>
                </div>
                `;
            }
        }

        const getWeatherDetails = (cityName, latitude, longitude) => {
            const WEATHER_API_URL = `https://api.openweathermap.org/data/2.5/forecast?lat=${latitude}&lon=${longitude}&appid=${API_KEY}`;

            fetch(WEATHER_API_URL).then(response => response.json()).then(data => {
                const uniqueForecastDays = [];
                const fiveDaysForecast = data.list.filter(forecast => {
                    const forecastDate = new Date(forecast.dt_txt).getDate();
                    if (!uniqueForecastDays.includes(forecastDate)) {
                        return uniqueForecastDays.push(forecastDate);
                    }
                });

                cityInput.val("");
                $(".current-weather").empty();
                $(".weather-cards").empty();
                $('#weather-message').text("");
                $('#weather-error').addClass('d-none');
                
                $.each(fiveDaysForecast, function(index, weatherItem) {
                    const html = createWeatherCard(cityName, weatherItem, index);
                    if (index === 0) {
                        $(".current-weather").append(html);
                    } else {
                        $(".weather-cards").append(html);
                    }
                });    
            }).catch(() => {
                $('#weather-message').text("An error occurred while fetching the weather forecast.");
                $('#weather-error').removeClass('d-none');
                cityInput.val("");
            });
        }

        const getCityCoordinates = () => {
            const cityName = cityInput.val().trim();
            if (cityName === "") return;
            const API_URL = `https://api.openweathermap.org/geo/1.0/direct?q=${cityName}&limit=1&appid=${API_KEY}`;
            
            fetch(API_URL).then(response => response.json()).then(data => {
                if (!data.length) {
                    $('#weather-message').text(`No coordinates found for ${cityName}.`);
                    $('#weather-error').removeClass('d-none');
                    cityInput.val("");
                    return;
                }
                const { lat, lon, name } = data[0];
                getWeatherDetails(name, lat, lon);
            }).catch(() => {
                $('#weather-message').text("An error occurred while fetching the coordinates.");
                $('#weather-error').removeClass('d-none');
                cityInput.val("");
            });
        }

        getCityCoordinates();
        $(".search-btn").click(function() {
            const cityName = cityInput.val().trim();
            if (cityName === "") {
                $('#weather-message').text("Nothing to search.");
                $('#weather-error').removeClass('d-none');
                $(".city-input").val("");
            } else {
                getCityCoordinates();
            }
        });

        // calendar app 
        var bookings = @json($calendar);
        
        var calendarEl = $('#calendar')[0];
        var calendar = new FullCalendar.Calendar(calendarEl, {
            headerToolbar: {
                left: 'prev,next today',
                center: 'title',
                right: 'dayGridMonth,timeGridWeek,timeGridDay,listMonth',
            },

            events: bookings,
            eventColor: '#2A93E8',
            displayEventTime: false,
            
            selectable: true,
            selectMirror: true,
            select: function(start, end, allDays) {
                $('#calendarModal').modal('toggle');
                $('#create-event-form')[0].reset();
                $('#event-start').val(start.startStr);
                $('#event-end').val(start.endStr);
                $('#venue').addClass('d-none');
            },  

            eventClick: function(info) {
                alert('ID: ' + info.event.id + 'Event: ' + info.event.title + 'Start: ' + info.event.start + 'End: ' + info.event.end + 'Desc: ' + info.event.extendedProps.desc + 'Type: ' + info.event.extendedProps.type + 'Audience: ' + info.event.extendedProps.audience + 'Venue: ' + info.event.extendedProps.venue);
            },
            eventContent: function(arg) {
                var eventType = arg.event.extendedProps && arg.event.extendedProps.type;
                var backgroundColor;

                switch (eventType) {
                    case 'Conference':
                        backgroundColor = '#FFD700'; // Yellow for Conference
                        break;
                    case 'Exhibition':
                        backgroundColor = '#2A93E8'; // Blue for Exhibition
                        break;
                    case 'Festive':
                        backgroundColor = '#FF1493'; // Pink for Festive
                        break;
                    case 'Seminar':
                        backgroundColor = '#32CD32'; // Green for Seminar
                        break;
                    case 'Training':
                        backgroundColor = '#FF4500'; // Orange for Training
                        break;
                    case 'Webinar':
                        backgroundColor = '#8A2BE2'; // Purple for Webinar
                        break;
                    case 'Workshop':
                        backgroundColor = '#00BFFF'; // Light Blue for Workshop
                        break;

                    default:
                        backgroundColor = '#2A93E8'; // Default color
                }

                var div = document.createElement('div');
                div.style.backgroundColor = backgroundColor;;
                div.textContent = arg.event.title;

                return { domNodes: [div] };
            },
        });

        $('#create-event-btn').click(function() {
            $('#calendarModal').modal('show');
            $('#create-event-form')[0].reset();
        });

        $('#campus').change(function() {
            var campusId = $(this).val();
            var venueDropdown = $('#venue');
            var venueError = $('#venue-error');
            
            venueDropdown.val('');
            $.get("{{ url('/getVenues') }}/" + campusId, function(data) {
                if(data.length > 0) {
                    venueError.addClass('d-none');
                    venueDropdown.empty();
                    $.each(data, function(index, venue) {
                        var option = $('<option></option>');
                        option.attr('value', venue.id).text(venue.venue_name);
                        venueDropdown.append(option);
                    });
                    venueDropdown.removeClass('d-none');
                } else {
                    venueDropdown.addClass('d-none');
                    venueError.removeClass('d-none').text('The campus does not have any available venues.');
                }
            });
        });

        // create event
        $('#create-event-form').submit(function(event) {
            event.preventDefault();
            
            var eventTitle = $('#event-title').val();
            var eventStart = $('#event-start').val();
            var eventEnd = $('#event-end').val();
            var eventType = $('#event-type').val();
            var targetAudience = $('#target-audience').val();
            var campus = $('#campus').val();
            var venue = $('#venue').val();

            $('.form-error').text('').addClass('d-none');

            if (!eventTitle) {
                $('#event-title').addClass('is-invalid');
                $('#event-title-error').text('Event title is a required field.').removeClass('d-none');
                return;
            }
            $('#event-title').removeClass('is-invalid');

            if (!eventStart) {
                $('#event-start').addClass('is-invalid');
                $('#event-start-error').text('Event start date is a required field.').removeClass('d-none');
                return;
            }
            $('#event-start').removeClass('is-invalid');

            if (!eventEnd) {
                $('#event-end').addClass('is-invalid');
                $('#event-end-error').text('Event end date is a required field.').removeClass('d-none');
                return;
            }
            $('#event-end').removeClass('is-invalid');

            if (!eventType) {
                $('#event-type').addClass('is-invalid');
                $('#event-type-error').text('Event type is a required field.').removeClass('d-none');
                return;
            }
            $('#event-type').removeClass('is-invalid');

            if (!targetAudience) {
                $('#target-audience').addClass('is-invalid');
                $('#target-audience-error').text('Target audience is a required field.').removeClass('d-none');
                return;
            }
            $('#target-audience').removeClass('is-invalid');

            if (!venue) {
                $('#venue-error').text('Venue is a required field.').removeClass('d-none');
                return;
            }
            
            Swal.fire({
                title: 'Create Event',
                text: 'Are you sure that all the data provided are correct?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Confirm'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        type: 'POST',
                        url: "{{ route('admin.store_event') }}",
                        data: $('#create-event-form').serialize(),
                        success: function(response) {
                            if (response.success) {
                                $('#calendarModal').modal('hide');
                                $('#create-event-form')[0].reset();
                                const newTab = window.open("{{ route('plan', ['eventId' => ':eventId']) }}".replace(':eventId', response.eventId), '_blank');
        
                                window.location.reload();
                                
                                if (newTab) {
                                    newTab.focus();
                                }
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
                }
            });
        });
        
        calendar.render();

        // pending events table
        $('#myTable').DataTable({
            "info": false,
            "searching": false,
            "lengthChange": false,
            "paging": false,
            "ordering": false,
            "scrollY": "350px",
            "scrollCollapse": true,
            "pageLength": 10 
        });
    });
</script>
@endsection
