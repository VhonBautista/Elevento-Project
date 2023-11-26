@extends('layouts.app')

@php
use Carbon\Carbon;
$user = Auth::user();
@endphp

@section('content')
<style>
.cover {
    position: relative;
    width: 100%;
    height: auto;
}
.cover img {
    width: 100%;
    height: 320px;
    object-fit: cover;
}
.floating-button {
    position: absolute;
    bottom: 0;
    right: 0;
    margin: 20px;
    padding: 10px 24px;
    font-size: 14px;
    border-radius: 50px;
    border: none;
    cursor: pointer;
    opacity: 0.6;
}
.wrapper::-webkit-scrollbar{
    width: 0;
}
.card-img-top {
    object-fit: cover;
    height: 190px;
}
</style>
<ul class="nav px-3 py-2 justify-content-between bg-primary">
    <div class="d-flex" style="width: 35%;">
        <li class="nav-item w-100">
            <form action="{{ route('home') }}" method="GET">
                <div class="input-group rounded-pill overflow-hidden w-100">
                    <span class="input-group-text text-secondary" style="border-right: none"><i class="fa-solid fa-magnifying-glass"></i></span>
                    <input type="text" style="border-left: none" name="search" class="form-control city-input" placeholder="Search Events" value="{{ request('search') }}" aria-describedby="search-btn">
                    <button class="btn search-btn btn-dark text-light" type="submit">Search</button>
                </div>
            </form>
        </li>
    </div>

    <div class="d-flex">
        @if ($user->role != "User")
        <li class="nav-item me-3">
            <a href="{{ route('dashboard') }}" class="btn btn-light rounded-pill d-flex align-items-center">
                <i class="fa-solid fa-gauge me-2"></i>
                Manage Events
            </a>
        </li>
        @endif

        <li class="nav-item ">
            <div class="dropdown">
                <button class="btn btn-light rounded-pill position-relative dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
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

{{-- Main Content --}}
<div class="container mt-3">
    @if (!($eventType || $targetAudience || $search))
        @if (!$upcomingEvents->isEmpty())
        <span class="text">Upcoming Events</span>
        <div class="container-fluid mt-2 mb-3">
            <div class="row flex-row flex-nowrap overflow-auto wrapper">
                @foreach($upcomingEvents as $upcomingEvent)
                <!-- event card item  -->
                <a href="{{  route('preview', ['eventId' => $upcomingEvent->id]) }}" class="card-link col-md-4 col-sm-6 text-dark" style="text-decoration: none;">
                    <div>
                        @if ($upcomingEvent->cover_photo)
                        <div class="position-relative">
                            <img class="card-img-top rounded" style="object-fit: cover; height: 190px;" src="{{ asset($upcomingEvent->cover_photo) }}" alt="Card image cap">
                            <div class="position-absolute translate-middle-x text-center" style="bottom: 10px; right: -14%;">
                                <span class="badge fs-5 m-0 bg-dark">{{ Carbon::parse($upcomingEvent->start_date)->format('M d') }} - {{ Carbon::parse($upcomingEvent->end_date)->format('d') }}</span>
                            </div>
                        </div>                    
                        @else
                        <div class="position-relative">
                            <img class="card-img-top rounded" style="object-fit: cover; height: 190px;" src="https://developers.elementor.com/docs/assets/img/elementor-placeholder-image.png" alt="Card image cap">
                            <div class="position-absolute translate-middle-x text-center" style="bottom: 10px; right: -14%;">
                                <span class="badge fs-5 m-0 bg-dark">{{ Carbon::parse($upcomingEvent->start_date)->format('M d') }} - {{ Carbon::parse($upcomingEvent->end_date)->format('d') }}</span>
                            </div>
                        </div>                    
                        @endif
                        <div class="p-0 pt-2">
                            <div class="container">
                                <div class="row">
                                    <div class="col-auto p-0">
                                        @if ($upcomingEvent->creator->profile_picture)
                                        <img src="{{ asset($upcomingEvent->creator->profile_picture) }}" alt="Avatar" class="rounded-circle mt-1" style="width: 40px; height: 40px;">
                                        @else
                                        <img src="{{ asset('asset/blank_profile.jpg') }}" alt="Avatar" class="rounded-circle" style="width: 40px; height: 40px;">
                                        @endif
                                    </div>
                                    <div class="col-7">
                                        <p class="fw-bold m-0">{{ Illuminate\Support\Str::limit($upcomingEvent->title, 22) }}</p>
                                        <p class="small text-secondary text-capitalize">{{ $upcomingEvent->event_type }} <span class="">|</span> For {{ $upcomingEvent->target_audience }} </p>
                                    </div>
                                    <div class="col-auto fw-bold small pe-0">
                                        <i class="fa-regular fa-heart"></i> {{ $upcomingEvent->hearts }}
                                        <i class="fa-regular ms-2 fa-circle-user"></i> {{ $upcomingEvent->register }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </a>
                <!-- event card item end -->
                @endforeach
            </div>
        </div><br>
        @endif
    @endif
    
    @if (!($eventType || $targetAudience || $search))
    <span class="text">Exciting Events</span>
    @else
    <span class="text">Results</span>
    @endif
    <div class="container mt-1" style="margin-bottom: 21px">
        <form action="{{ route('home') }}" method="GET">
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
            </ul>
        </form>                                                   

        <!-- dislpay project here  -->
        @if (!$activeProjects->isEmpty())
        <div class="row d-flex flex-wrap g-3" style="margin-bottom: 60px;">
            @foreach($activeProjects as $activeProject)
            <!-- event card item  -->
            <a href="{{ route('preview', ['eventId' => $activeProject->id]) }}" class="card-link col-md-4 col-sm-6 text-dark" style="text-decoration: none;">
                <div class="">
                    @if ($activeProject->cover_photo)
                    <div class="position-relative">
                        <img class="card-img-top rounded" style="object-fit: cover; height: 190px;" src="{{ asset($activeProject->cover_photo) }}" alt="Card image cap">
                        <div class="position-absolute translate-middle-x text-center" style="bottom: 10px; right: -14%;">
                            <span class="badge fs-5 m-0 bg-dark">{{ Carbon::parse($activeProject->start_date)->format('M d') }} - {{ Carbon::parse($activeProject->end_date)->format('d') }}</span>
                        </div>
                    </div>                    
                    @else
                    <div class="position-relative">
                        <img class="card-img-top rounded" style="object-fit: cover; height: 190px;" src="https://developers.elementor.com/docs/assets/img/elementor-placeholder-image.png" alt="Card image cap">
                        <div class="position-absolute translate-middle-x text-center" style="bottom: 10px; right: -14%;">
                            <span class="badge fs-5 m-0 bg-dark">{{ Carbon::parse($activeProject->start_date)->format('M d') }} - {{ Carbon::parse($activeProject->end_date)->format('d') }}</span>
                        </div>
                    </div>                    
                    @endif
                    <div class="p-0 pt-2">
                        <div class="container">
                            <div class="row">
                                <div class="col-auto p-0">
                                    @if ($activeProject->creator->profile_picture)
                                    <img src="{{ asset($activeProject->creator->profile_picture) }}" alt="Avatar" class="rounded-circle mt-1" style="width: 40px; height: 40px;">
                                    @else
                                    <img src="{{ asset('asset/blank_profile.jpg') }}" alt="Avatar" class="rounded-circle" style="width: 40px; height: 40px;">
                                    @endif
                                </div>
                                <div class="col-7">
                                    <p class="fw-bold m-0">{{ Illuminate\Support\Str::limit($activeProject->title, 22) }}</p>
                                    <p class="small text-secondary text-capitalize">{{ $activeProject->event_type }} <span class="">|</span> For {{ $activeProject->target_audience }} </p>
                                </div>
                                <div class="col-auto fw-bold small pe-0">
                                    <i class="fa-regular fa-heart"></i> {{ $activeProject->hearts }}
                                    <i class="fa-regular ms-2 fa-circle-user"></i> {{ $activeProject->register }}
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
@endsection

@section('script')
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>
<script>
    var assetUrl = "{{ asset('') }}";
    $(document).ready(function() {

    });
</script>
@endsection
