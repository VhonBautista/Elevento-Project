@extends('layouts.app')

@php
use Carbon\Carbon;
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
            <li>
                <a href="{{ route('dashboard') }}">
                    <i class="fa-solid fa-gauge"></i>
                    <span class="side-link-name">Home</span>
                </a>
            </li>
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
                                                <img src="{{ asset($activeProject->event->creator->profile_picture) }}" alt="Avatar" class="rounded-circle mt-1" style="width: 40px; height: 40px;">
                                                @else
                                                <img src="{{ asset('asset/blank_profile.jpg') }}" alt="Avatar" class="rounded-circle" style="width: 40px; height: 40px;">
                                                @endif
                                            </div>
                                            <div class="col-7">
                                                <p class="small text-truncate m-0"><strong>{{ $activeProject->event->title }}</strong></p>
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
                    
                    <ul class="nav nav-tabs" id="myTab" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="project-tab" data-bs-toggle="tab" data-bs-target="#project-tab-pane" type="button" role="tab" aria-controls="project-tab-pane" aria-selected="true">Projects</button>
                        </li>
                        @if (!($user->role == "Admin" || $user->role == "Co-Admin"))
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="approval-tab" data-bs-toggle="tab" data-bs-target="#approval-tab-pane" type="button" role="tab" aria-controls="approval-tab-pane" aria-selected="false">For Approval</button>
                        </li>
                        @endif
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="approval-tab" data-bs-toggle="tab" data-bs-target="#done-tab-pane" type="button" role="tab" aria-controls="done-tab-pane" aria-selected="false">Concluded Events</button>
                        </li>
                    </ul>
                    
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
                                    <li class="nav-item">
                                        <p class="nav-link text-dark" aria-disabled="true">Filter By Status:</p>
                                    </li>
                                    <li class="nav-item">
                                        <select name="eventStatus" class="form-control" onchange="this.form.submit()">
                                            <option value="" {{ request('eventStatus') == '' ? 'selected' : '' }}>All</option>
                                            <option value="planning" {{ request('eventStatus') == 'planning' ? 'selected' : '' }}>Planning Phase</option>
                                            <option value="happening" {{ request('eventStatus') == 'happening' ? 'selected' : '' }}>Ongoing</option>
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
                                        @if ($project->event->cover_photo)
                                        <img class="card-img-top rounded" style="object-fit: cover; height: 190px;" src="{{ asset($project->event->cover_photo) }}" alt="Card image cap">
                                        @else
                                        <img class="card-img-top rounded" style="object-fit: cover; height: 190px;" src="https://developers.elementor.com/docs/assets/img/elementor-placeholder-image.png" alt="Card image cap">
                                        @endif
                                        <div class="p-0 pt-2">
                                            <div class="container">
                                                <div class="row">
                                                    <div class="col-auto p-0">
                                                        @if ($project->event->creator->profile_picture)
                                                        <img src="{{ asset($project->event->creator->profile_picture) }}" alt="Avatar" class="rounded-circle mt-1" style="width: 40px; height: 40px;">
                                                        @else
                                                        <img src="{{ asset('asset/blank_profile.jpg') }}" alt="Avatar" class="rounded-circle" style="width: 40px; height: 40px;">
                                                        @endif
                                                    </div>
                                                    <div class="col-7">
                                                        <h6 class="small text-truncate m-0"><strong>{{ $project->event->title }} </strong>@if($project->event->status == "Happening") <span class="badge bg-success">Ongoing</span> @endif</h6>
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

                        @if ($user->role != "Admin")
                        <div class="tab-pane fade p-4" id="approval-tab-pane" role="tabpanel" aria-labelledby="approval-tab" tabindex="0">
                            <!-- dislpay project here  -->
                                @if (!$approvalProjects->isEmpty())
                                <div class="row d-flex flex-wrap g-3" style="margin-bottom: 60px;">
                                    @foreach($approvalProjects as $approvalProject)
                                    <!-- event card item  -->
                                    <a class="card-link col-md-4 col-sm-6 text-dark" type="button" style="text-decoration: none;" data-bs-toggle="modal" data-bs-target="#eventDetailsModal{{ $approvalProject->event->id }}">
                                        <div class="">
                                            @if ($approvalProject->event->cover_photo)
                                            <img class="card-img-top rounded" style="object-fit: cover; height: 190px;" src="{{ asset($approvalProject->event->cover_photo) }}" alt="Card image cap">
                                            @else
                                            <img class="card-img-top rounded" style="object-fit: cover; height: 190px;" src="https://developers.elementor.com/docs/assets/img/elementor-placeholder-image.png" alt="Card image cap">
                                            @endif
                                            <div class="p-0 pt-2">
                                                <div class="container">
                                                    <div class="row">
                                                        <div class="col-auto p-0">
                                                            @if ($approvalProject->event->creator->profile_picture)
                                                            <img src="{{ asset($approvalProject->event->creator->profile_picture) }}" alt="Avatar" class="rounded-circle" style="width: 40px; height: 40px;">
                                                            @else
                                                            <img src="{{ asset('asset/blank_profile.jpg') }}" alt="Avatar" class="rounded-circle" style="width: 40px; height: 40px;">
                                                            @endif
                                                        </div>
                                                        <div class="col-7">
                                                            <p class="small text-truncate m-0"><strong>{{ $approvalProject->event->title }}</strong></p>
                                                            <p class="small text-truncate text-secondary m-0">Waiting for Approval</p>
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

                                    <!-- Modal -->
                                    <div class="modal fade" id="eventDetailsModal{{ $approvalProject->event->id }}" tabindex="-1" aria-labelledby="eventDetailsModalLabel" aria-hidden="true">
                                        <div class="modal-dialog modal-sm">
                                            <div class="modal-content">
                                                <div class="modal-header p-1 bg-primary">
                                                </div>
                                                <div class="modal-body text-center">
                                                    <h6 class="text-capitalize m-0 text fw-bold" id="eventDetailsModalLabel">{{ $approvalProject->event->title }} Details</h6>
                                                    <p class="m-0 form-text small text-secondary">Waiting for approval</p>
                                                    <hr>
                                                    <div class="container mt-0 d-flex flex-column">   
                                                        <h6 class="text-capitalize mb-2 m-0 me-3"><strong>Event type:</strong> {{ $approvalProject->event->event_type }}</h6>
                                                        <h6 class="text-capitaliz mb-2 m-0 me-3"><strong>Target Audience:</strong> {{ $approvalProject->event->target_audience }}</h6>
                                                        <h6 class="text-capitaliz mb-2 m-0 me-3"><strong>Main Venue:</strong> {{ $approvalProject->event->venue->venue_name }}</h6>
                                                        <h6 class="text-capitaliz mb-4 m-0 me-3"><strong>Venue Capacity:</strong> {{ $approvalProject->event->venue->capacity }}</h6>
                                                        <h5 class="m-0">From <strong>{{ Carbon::parse($approvalProject->event->start_date)->format('M d') }}</strong> to <strong>{{ Carbon::parse($approvalProject->event->end_date)->subDay()->format('M d') }}</strong></h5>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>  
                            @else
                                <div class="container w-100 text-center" style="margin-top: 100px; margin-bottom: 180px;">
                                    No events for approval
                                </div>
                            @endif
                        </div>
                        @endif

                        <div class="tab-pane fade p-4" id="done-tab-pane" role="tabpanel" aria-labelledby="done-tab" tabindex="0">
                            <!-- dislpay project here  -->
                                @if (!$doneProjects->isEmpty())
                                <div class="row d-flex flex-wrap g-3" style="margin-bottom: 60px;">
                                    @foreach($doneProjects as $doneProject)
                                    <!-- event card item  -->
                                    <a href="{{  route('preview', ['eventId' => $doneProject->event->id]) }}" target="_blank" class="card-link col-md-4 col-sm-6 text-dark" style="text-decoration: none;">
                                        <div class="">
                                            @if ($doneProject->event->cover_photo)
                                            <img class="card-img-top rounded" style="object-fit: cover; height: 190px;" src="{{ asset($doneProject->event->cover_photo) }}" alt="Card image cap">
                                            @else
                                            <img class="card-img-top rounded" style="object-fit: cover; height: 190px;" src="https://developers.elementor.com/docs/assets/img/elementor-placeholder-image.png" alt="Card image cap">
                                            @endif
                                            <div class="p-0 pt-2">
                                                <div class="container">
                                                    <div class="row">
                                                        <div class="col-auto p-0">
                                                            @if ($doneProject->event->creator->profile_picture)
                                                            <img src="{{ asset($doneProject->event->creator->profile_picture) }}" alt="Avatar" class="rounded-circle" style="width: 40px; height: 40px;">
                                                            @else
                                                            <img src="{{ asset('asset/blank_profile.jpg') }}" alt="Avatar" class="rounded-circle" style="width: 40px; height: 40px;">
                                                            @endif
                                                        </div>
                                                        <div class="col-7">
                                                            <p class="small text-truncate m-0"><strong>{{ $doneProject->event->title }}</strong></p>
                                                            <p class="small text-truncate text-secondary m-0">Waiting for Approval</p>
                                                        </div>
                                                        <div class="col-auto fw-bold small pe-0">
                                                            <i class="fa-regular fa-heart"></i> {{ $doneProject->event->hearts }}
                                                            <i class="fa-regular ms-2 fa-circle-user"></i> {{ $doneProject->event->register }}
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
                                <div class="container w-100 text-center" style="margin-top: 100px; margin-bottom: 180px;">
                                    No events concluded yet
                                </div>
                            @endif
                        </div>
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

        // create event
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
                                
                                @if ($user->role != 'Organizer')
                                    const newTab = window.open("{{ route('plan', ['eventId' => ':eventId']) }}".replace(':eventId', response.eventId), '_blank');
                                    window.location.reload();
                                    if (newTab) {
                                        newTab.focus();
                                    }
                                @else
                                    Swal.fire({
                                        title: 'Request Created',
                                        text: 'Event request sent successfully. Please wait for the confirmation by the admin.',
                                        icon: 'success',
                                        confirmButtonColor: '#3085d6',
                                        confirmButtonText: 'OK'
                                    }).then((result) => {
                                        window.location.reload();
                                    });
                                @endif
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
    });
</script>
@endsection
