@extends('layouts.app')

@php
$user = Auth::user();
@endphp

@section('content')
@if ( $user->role != "User" )

<style>
.wrapper::-webkit-scrollbar{
    width: 0;
}
.card-img-top {
    object-fit: cover;
    height: 190px;
}
</style>
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
            @if ( $user->role == "Organizer" )
            <li>
                <!-- todo: organizer home -->
                <a href="{{ route('admin.dashboard') }}">
                    <i class="fa-solid fa-gauge"></i>
                    <span class="side-link-name">Home</span>
                </a>
            </li>
            @else
            <li>
                <a href="{{ route('admin.dashboard') }}">
                    <i class="fa-solid fa-gauge"></i>
                    <span class="side-link-name">Home</span>
                </a>
            </li>
            @endif
            <li>
                <a href="{{ route('projects') }}" class="active">
                    <i class="fa-solid fa-folder-open"></i>
                    <span class="side-link-name">Projects</span>
                </a>
            </li>
            <!-- <li>
                <a href="">
                    <i class="fa-solid fa-calendar-days"></i>
                    <span class="side-link-name">Tasks</span>
                </a>
            </li> -->
            @if ( $user->role != "Organizer" )
            @if (($user->role == 'Co-Admin' && $user->manage_event == 1) || $user->role == 'Admin')
            <li>
                <a href="{{ route('admin.approval') }}">
                    <i class="fa-solid fa-calendar-days"></i>
                    <span class="side-link-name">Event Approvals</span>
                </a>
            </li>
            @endif
            <li>
                <a href="{{ route('admin.management') }}">
                    <i class="fa-solid fa-toolbox"></i>
                    <span class="side-link-name">Utilities</span>
                </a>
            </li>
            @endif
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
        @endif
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
                    @if ( $user->role == "Organizer" )
                    <button type="button" id="create-event-btn" class="btn px-4 btn-primary rounded-pill mx-1">
                        <i class="fa-solid me-2 fa-calendar-plus"></i>
                        Schedule an Event
                    </button>
                    @elseif ( $user->role == "Co-Admin" || $user->role == 'Admin')
                    <button type="button" id="create-event-btn" class="btn px-4 btn-primary rounded-pill mx-1">
                        <i class="fa-solid me-2 fa-calendar-plus"></i>
                        Create Event
                    </button>
                    @endif
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
                        <li><span class="dropdown-item">{{ ucfirst($user->username) }}</span></li>
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
            <div class="container mt-2">
                @if (!$activeProjects->isEmpty())
                <span class="text">Active Events</span>
                <div class="container-fluid mt-2 mb-3">
                    <div class="row flex-row flex-nowrap overflow-auto wrapper">
                        @foreach($activeProjects as $activeProject)
                        <!-- event card item  -->
                        <a href="{{  route('plan', ['eventId' => $activeProject->event->id]) }}" target="_blank" class="card-link col-md-4 col-sm-6 text-dark" style="text-decoration: none;">
                            <div>
                                @if ($activeProject->event->cover_photo)
                                <img class="card-img-top rounded" style="object-fit: cover; height: 190px;" src="{{ asset($activeProject->event->cover_photo) }}" alt="Card image cap">
                                @else
                                <img class="card-img-top rounded" style="object-fit: cover; height: 190px;" src="https://developers.elementor.com/docs/assets/img/elementor-placeholder-image.png" alt="Card image cap">
                                @endif
                                <div class="p-0 pt-2">
                                    <div class="container">
                                        <div class="row">
                                            <div class="col-auto p-0">
                                                @if ($activeProject->event->creator->profile_picture)
                                                <img src="{{ asset($activeProject->event->creator->profile_picture) }}" alt="Avatar" class="rounded-circle mt-1" style="width: 40px;">
                                                @else
                                                <img src="{{ asset('asset/blank_profile.jpg') }}" alt="Avatar" class="rounded-circle" style="width: 40px;">
                                                @endif
                                            </div>
                                            <div class="col-7">
                                                <strong class="small text-truncate">{{ $activeProject->event->title }}</strong>
                                                @if ($activeProject->event->description)
                                                <p class="small text-truncate m-0">{{ $activeProject->event->description }}</p>
                                                @else
                                                <p class="small text-truncate text-secondary m-0">No Description</p>
                                                @endif
                                            </div>
                                            <div class="col-auto fw-bold small pe-0">
                                                <i class="fa-regular fa-heart"></i> {{ $activeProject->event->hearts }}
                                                <i class="fa-regular ms-2 fa-circle-user"></i> {{ $activeProject->event->register }}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </a>
                        <!-- event card item end -->
                        @endforeach
                    </div>
                </div>
                <hr>
                @endif

                <span class="text">Projects</span>
                <div class="container mt-1" style="margin-bottom: 21px">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif
                    
                    @if (!$user->role == "Admin")
                    <ul class="nav nav-tabs" id="myTab" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="project-tab" data-bs-toggle="tab" data-bs-target="#project-tab-pane" type="button" role="tab" aria-controls="project-tab-pane" aria-selected="true">Projects</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="approval-tab" data-bs-toggle="tab" data-bs-target="#approval-tab-pane" type="button" role="tab" aria-controls="approval-tab-pane" aria-selected="false">For Approval</button>
                        </li>
                    </ul>
                    @endif
                    
                    <div class="tab-content" id="myTabContent">
                        <div class="tab-pane fade show active" id="project-tab-pane" role="tabpanel" aria-labelledby="project-tab" tabindex="0">
                            <form action="{{ route('projects') }}" method="GET">
                                <ul class="nav nav-pills mt-3">
                                    <li class="nav-item">
                                        <p class="nav-link text-dark" aria-disabled="true">Filter By Type:</p>
                                    </li>
                                    <li class="nav-item">
                                        <select name="eventType" class="form-control" onchange="this.form.submit()">
                                            <option value="" {{ request('eventType') == '' ? 'selected' : '' }}>All</option>
                                            @foreach ($eventTypes as $eventType)
                                                <option value="{{ $eventType->event_type }}" {{ request('eventType') == $eventType->event_type ? 'selected' : '' }}>
                                                    {{ $eventType->event_type }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </li>
                                    <li class="nav-item">
                                        <p class="nav-link text-dark" aria-disabled="true">Filter By Audience:</p>
                                    </li>
                                    <li class="nav-item">
                                        <select name="targetAudience" class="form-control" onchange="this.form.submit()">
                                            <option value="" {{ request('targetAudience') == '' ? 'selected' : '' }}>All</option>
                                            <option value="student" {{ request('targetAudience') == 'student' ? 'selected' : '' }}>Student</option>
                                            <option value="faculty" {{ request('targetAudience') == 'faculty' ? 'selected' : '' }}>Faculty</option>
                                        </select>
                                    </li>
                                    <li class="nav-item ms-auto">
                                        <div class="d-flex">
                                            <input class="form-control me-2" type="search" placeholder="Search" aria-label="Search" name="search" value="{{ request('search') }}">
                                            <button class="btn btn-outline-primary" type="submit">Search</button>
                                        </div>
                                    </li>
                                </ul>
                            </form>                                                   

                            <!-- dislpay project here  -->
                            @if (!$projects->isEmpty())
                            <div class="row d-flex flex-wrap g-3" style="margin-bottom: 60px;">
                                @foreach($projects as $project)
                                <!-- event card item  -->
                                <a href="{{  route('plan', ['eventId' => $project->event->id]) }}" target="_blank" class="card-link col-md-4 col-sm-6 text-dark" style="text-decoration: none;">
                                    <div class="">
                                        <img class="card-img-top rounded" style="object-fit: cover; height: 190px;" src="https://developers.elementor.com/docs/assets/img/elementor-placeholder-image.png" alt="Card image cap">
                                        <div class="p-0 pt-2">
                                            <div class="container">
                                                <div class="row">
                                                    <div class="col-auto p-0">
                                                        @if ($project->event->creator->profile_picture)
                                                        <img src="{{ asset($project->event->creator->profile_picture) }}" alt="Avatar" class="rounded-circle mt-1" style="width: 40px;">
                                                        @else
                                                        <img src="{{ asset('asset/blank_profile.jpg') }}" alt="Avatar" class="rounded-circle" style="width: 40px;">
                                                        @endif
                                                    </div>
                                                    <div class="col-7">
                                                        <strong class="small text-truncate">{{ $project->event->title }}</strong>
                                                        @if ($project->event->description)
                                                        <p class="small text-truncate m-0">{{ $project->event->description }}</p>
                                                        @else
                                                        <p class="small text-truncate text-secondary m-0">No Description</p>
                                                        @endif
                                                    </div>
                                                    <div class="col-auto fw-bold small pe-0">
                                                        <i class="fa-regular fa-heart"></i> {{ $project->event->hearts }}
                                                        <i class="fa-regular ms-2 fa-circle-user"></i> {{ $project->event->register }}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </a>
                                <!-- event card item end -->
                                @endforeach
                            </div>  
                            @else
                                <div class="container w-100 text-center" style="margin-top: 70px; margin-bottom: 210px;">
                                    No events found
                                </div>
                            @endif
                        </div>

                        @if (!$user->role == "Admin")
                        <div class="tab-pane fade" id="approval-tab-pane" role="tabpanel" aria-labelledby="approval-tab" tabindex="0">
                            <!-- dislpay project here  -->
                                @if (!$approvalProjects->isEmpty())
                                <div class="row d-flex flex-wrap g-3" style="margin-bottom: 60px;">
                                    @foreach($approvalProjects as $approvalProject)
                                    <!-- event card item  -->
                                    <a href="{{  route('plan', ['eventId' => $approvalProject->event->id]) }}" target="_blank" class="card-link col-md-4 col-sm-6 text-dark" style="text-decoration: none;">
                                        <div class="">
                                            <img class="card-img-top rounded" style="object-fit: cover; height: 190px;" src="https://developers.elementor.com/docs/assets/img/elementor-placeholder-image.png" alt="Card image cap">
                                            <div class="p-0 pt-2">
                                                <div class="container">
                                                    <div class="row">
                                                        <div class="col-auto p-0">
                                                            @if ($approvalProject->event->creator->profile_picture)
                                                            <img src="{{ asset($approvalProject->event->creator->profile_picture) }}" alt="Avatar" class="rounded-circle mt-1" style="width: 40px;">
                                                            @else
                                                            <img src="{{ asset('asset/blank_profile.jpg') }}" alt="Avatar" class="rounded-circle" style="width: 40px;">
                                                            @endif
                                                        </div>
                                                        <div class="col-7">
                                                            <strong class="small text-truncate">{{ $approvalProject->event->title }}</strong>
                                                            @if ($approvalProject->event->description)
                                                            <p class="small text-truncate m-0">{{ $approvalProject->event->description }}</p>
                                                            @else
                                                            <p class="small text-truncate text-secondary m-0">No Description</p>
                                                            @endif
                                                        </div>
                                                        <div class="col-auto fw-bold small pe-0">
                                                            <i class="fa-regular fa-heart"></i> {{ $approvalProject->event->hearts }}
                                                            <i class="fa-regular ms-2 fa-circle-user"></i> {{ $approvalProject->event->register }}
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </a>
                                    <!-- event card item end -->
                                    @endforeach
                                </div>  
                            @else
                                <div class="container w-100 text-center" style="margin-top: 100px; margin-bottom: 210px;">
                                    No events for approval
                                </div>
                            @endif
                        </div>
                        @endif
                    </div>
                </div>

                <footer class="py-4 bg-light">
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
    });
</script>
@endsection
