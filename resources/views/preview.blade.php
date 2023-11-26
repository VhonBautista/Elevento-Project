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
            <div class="input-group rounded-pill overflow-hidden w-100">
                <span class="input-group-text text-secondary" style="border-right: none"><i class="fa-solid fa-magnifying-glass"></i></span>
                <input type="text" style="border-left: none" class="form-control city-input" placeholder="Search Events" aria-describedby="search-btn">
                <button class="btn search-btn btn-dark text-light" type="button" id="search-btn">Search</button>
            </div>
        </li>
    </div>

    <div class="d-flex">
        <li class="nav-item me-2">
            <a href="{{ route('home') }}" class="btn btn-light px-4 rounded-pill mx-1">
                <i class="fa-solid fa-globe me-2"></i>
                Explore Events
            </a>
        </li>

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

{{-- Cover Image --}}
<div class="cover">
    @if ($event->cover_photo)
    <img src="{{ asset($event->cover_photo) }}" alt="Your Image">
    @else
    <img src="https://developers.elementor.com/docs/assets/img/elementor-placeholder-image.png" alt="Your Image">
    @endif
    
    @if ($hasProject)
        @if ($event->staus != 'Done' || $event->staus != 'Happening')
        <a class="floating-button btn btn-light me-3" href="{{ route('plan', ['eventId' => $event->id]) }}">
            @if ($event->status == 'Done')        
            View More Details
            @else
            Manage Event
            @endif
        </a>
        @endif
    @endif
</div>

<div class="bg-light px-2 py-3">
    <div class="d-flex align-items-center mx-4 justify-content-between">
        <div class="d-flex g-2 align-items-center">
            <div class="rounded-circle overflow-hidden">
                <img src="@if (Auth::user()->profile_picture == null) {{ asset('asset/blank_profile.jpg') }} @else {{ asset(Auth::user()->profile_picture) }} @endif" width="55" height="55" alt="Profile Image">
            </div>
            <div class="ms-3">
                <div class="row">
                    <h4 class="fw-bold m-0">{{ $event->title }} <span class="text-primary fs-5">( {{ Carbon::parse($event->start_date)->format('M d') }} - {{ Carbon::parse($event->end_date)->format('M d') }} )</span></h4>
                </div>
                <div class="row">
                    <p class="text-secondary m-0">By {{ $event->creator->username }} @if ($hasMultipleUsers) and Team @endif <span class="mx-2">|</span> <i class="fa-regular fa-circle-user m-0 p-0"></i> Total Registration: {{ $event->register }}</p>
                </div>
            </div>
        </div>        
        <div class="d-flex g-2 align-items-center">
            <div class="d-none d-flex flex-column justify-content-center align-items-center" id="showRegisteredBtn">
                <button type="button" class="btn mx-2 btn-outlined rounded py-2 px-5" disabled>Registered</button>
                <div>
                    <a type="button" class="small link-underline link-underline-opacity-0" data-bs-toggle="modal" data-bs-target="#qrModal">View QR Code Pass</a>
                </div>
            </div>
            @if ($registration)
            <div class="d-flex flex-column justify-content-center align-items-center">
                <button type="button" class="btn mx-2 btn-outlined rounded py-2 px-5" disabled>Registered</button>
                <div>
                    <a type="button" class="small link-underline link-underline-opacity-0" data-bs-toggle="modal" data-bs-target="#qrModal">View QR Code Pass</a>
                </div>
            </div>
            @else
            <button type="button" class="btn mx-2 btn-primary rounded-pill py-2 px-5" data-bs-toggle="modal" data-bs-target="#registerModal" id="showRegisterBtn" @if ($event->status != 'Active') disabled @endif>Register</button>
            @endif

            <!-- Modal for Register -->
            <div class="modal fade" id="registerModal" tabindex="-1" aria-labelledby="registerModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-md">
                    <div class="modal-content">
                        <div class="modal-header p-1 px-3 bg-primary">
                        </div>
                        <div class="modal-body">
                            <h5 class="text m-0 text-center">Register to {{ $event->title }}</h5><hr>
                            <p class="small text-dark text-center">By registering for this event, you grant the event organizer access to your campus details, including your name, sex, department, campus, and user ID. This information will solely be used for the purpose of this event and will not be utilized by organizers for personal use.</p>
                            <form id="registrationForm" action="{{ route('preview.register') }}" method="post">
                                @csrf
                            
                                <input type="hidden" name="eventId" value="{{ $event->id }}">
                            
                                <div class="w-100 px-4">
                                    <label class="form-label">Email</label>
                                    <input type="text" class="form-control" value="{{ $user->email }}" name="email" disabled>
                                    <small class="form-text text-muted">Make sure that your email is correct.</small>
                                </div>
                            
                                <div class="w-100 pt-3">
                                    <button type="button" id="registerBtn" class="btn my-1 w-100 btn-primary">Register</button>
                                    <button type="button" class="btn my-1 w-100 btn-secondary" data-bs-dismiss="modal">Close</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Modal for QR -->
            <div class="modal fade" id="qrModal" tabindex="-1" aria-labelledby="qrModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-md">
                    <div class="modal-content" id="qrModalContent">
                        <div class="modal-header py-1 bg-primary">
                            <div class="text-center p-1 w-100">
                                <p class="m-0 text-light" style="font-size: 20px">Your QR Code Pass</p>
                                <small class="form-text text-light text-center">Make sure to download your QR code attendance pass.</small>
                            </div>
                        </div>
                        <div class="modal-body">
                            <h5 class="text m-0 text-center">{{ $event->title }}</h5>
                        
                            {{-- QR display here --}}
                            <div id="qrCode" class="text-center p-3">
                                <img src="@if ($registration) {{ 'https://api.qrserver.com/v1/create-qr-code/?size=200x200&data=' . $registration->qr_code }}  @endif" id="qrImage">
                            </div>
                        
                            <button id="downloadQRBtn" class="btn btn-primary my-1 w-100 rounded-pill">Download QR Pass</button>
                            <button type="button" class="btn my-1 w-100 btn-light text-dark rounded-pill" data-bs-dismiss="modal">Close</button>
                        </div>
                    </div>
                </div>
            </div>

            <form action="{{ route('preview.updateHearts') }}" method="post">
                @csrf
                <input type="hidden" name="eventId" value="{{ $event->id }}">

                <button type="submit" class="btn mx-2 btn-outline-secondary rounded py-2 px-3" @if ($event->status == 'Planning') disabled @endif>@if($existingFavorite) <i class="fa-solid fa-heart text-danger"></i> @else <i class="fa-regular fa-heart"></i> @endif {{ $event->hearts }}</button>
            </form>
        </div>
    </div>
</div>
<hr class="mt-0">

{{-- Main content --}}
<style>
    .carousel-control-prev,
    .carousel-control-next {
        width: 60px;
    }

    .carousel-control-prev:hover,
    .carousel-control-next:hover {
        background-color: rgb(0, 0, 0, 0.5);
    }
</style>
<div class="container mt-3">
    <div class="row">
        <div class="col-lg-12">
            <span class="text" style="font-size: 24px;">About the Event</span>
            <div class="container px-4 mb-3 d-flex">   
                <h6 class="text-capitalize m-0 me-3"><strong>Event type:</strong> {{ $event->event_type }}</h6>
                <h6 class="text-capitalize m-0 me-3"><strong>Target Audience:</strong> {{ $event->target_audience }}</h6>
                <h6 class="text-capitalize m-0 me-3"><strong>Venue Capacity:</strong> {{ $event->venue->capacity }}</h6>
                <h6 class="m-0 me-3">From <strong>{{ Carbon::parse($event->start_date)->format('M d') }}</strong> to <strong>{{ Carbon::parse($event->end_date)->subDay()->format('M d') }}</strong></h6>
            </div>
            <div class="container px-4">
                <p class="fw-bold fs-5 m-0">Event Description</p>
                <p>
                    @if($event->description) {{ $event->description }} @else No description to show. @endif
                </p>
            </div>
            <br>
            <hr class="m-0 mb-3">
        </div>
        <div class="col-lg-8 mb-4">
            <span class="text" style="font-size: 24px;">Main Venue</span>
            <div class="mt-2 mx-auto mb-3 rounded" style="height: 210px; overflow: hidden;">
                @if ($event->venue->image)
                <img src="{{ asset($event->venue->image) }}" class="mx-3 rounded" style="width: 100%; height: 100%; object-fit: cover;">
                @else
                <img src="https://developers.elementor.com/docs/assets/img/elementor-placeholder-image.png" class="mx-3 rounded" style="width: 100%; height: 100%; object-fit: cover;">
                @endif
            </div>  
            @if ($event->venue->image)
            <div class="w-100 small text-secondary text-center" style="margin-top: -10px">
                <p>{{ $event->venue->venue_name }}</p>
            </div>
            @else
            <div class="w-100 small text-secondary text-center" style="margin-top: -10px">
                <p>{{ $event->venue->venue_name . ' - ' . $event->venue->campus }}. (No image available for this venue.)</p>
            </div>
            @endif 
            <span class="text" style="font-size: 24px;">Program Flow</span>
            @if ($segments->isNotEmpty())
                <div id="segmentsCarousel" class="carousel slide mt-2 mx-4">
                    <div class="carousel-inner">
                        @foreach ($segments as $index => $segment)
                            <div class="carousel-item {{ $index === 0 ? 'active' : '' }}">
                                <!-- Sub-Events Item -->
                                <div class="">
                                    <div class="card-header p-4 rounded bg-dark">
                                        <p class="card-title m-0 fs-5 text-light" style="font-size: 14px;">{{ Carbon::parse($segment->date)->format('F j, Y (l)') }}</p>
                                    </div>
                                    <div class="card-body pb-0">
                                        <div id="flows-container">
                                            @if ($segment->flows->isNotEmpty())
                                                @php
                                                    $sortedFlows = $segment->flows->sortBy(function ($flow) {
                                                        return array_search($flow->timeline, ['Morning','Noon','Afternoon','Evening','Midnight']);
                                                    });
                                                @endphp
            
                                                @foreach ($sortedFlows as $flow)
                                                <!-- Flow Item -->
                                                <div class="card mt-3">
                                                    <div class="card-header d-flex justify-content-between align-items-center">
                                                        <h5 class="card-title m-0">{{ $flow->timeline . ' (' . \Carbon\Carbon::parse($flow->start_time)->format('g:i A') . ' - ' . \Carbon\Carbon::parse($flow->end_time)->format('g:i A') . ')' }}</h5>
                                                    </div>
                                                    <div class="card-body">
                                                        <ul class="list-group list-group-flush flow-item" data-flow-id="{{ $flow->id }}">
                                                            @php
                                                                $flowList = explode(', ', $flow->list);
                                                            @endphp
                                                            @foreach ($flowList as $item)
                                                                <li class="list-group-item">{{ $item }}</li>
                                                            @endforeach
                                                        </ul>
                                                        <form class="w-100 edit-flow-form d-none" data-flow-id="{{ $flow->id }}">
                                                            @csrf
            
                                                            <input type="hidden" name="event_id" value="{{ $event->id }}">
                                                            <input type="hidden" name="segment_id" value="{{ $segment->id }}">
                                                            <input type="hidden" name="flow_id" value="{{ $flow->id }}">
            
                                                            <textarea name="new_flow" class="form-control" id="new-flow" placeholder="Registration 8:00 AM, Opening 9:00 AM, and so on." rows="3">{{ $flow->list }}</textarea>
                                                            <small class="form-text text-muted">Note: Please separate each flow with a comma (','). For example: Registration 8:00 AM, Opening 9:00 AM, and so on.</small>
                                                            
                                                            <div id="new-flow-error" class="form-error mt-2 text-danger small d-none"></div>
                                                            
                                                            <div class="w-100 d-flex mt-3 justify-content-end">
                                                                <button type="submit" class="mx-1 btn btn-primary">Save</button>
                                                                <button type="button" class="cancel-edit-btn mx-1 btn btn-secondary" data-flow-id="{{ $flow->id }}">Cancel</button>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                                <!-- Flow Item End -->
                                                @endforeach
                                            @else
                                                <div class="text-center m-4">
                                                    <p>No flows available for this segment.</p>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <!-- Sub-Events Item End -->
                            </div>
                        @endforeach
                    </div>
                    @if ($segments->count() > 1)
                        <button class="carousel-control-prev rounded" type="button" data-bs-target="#segmentsCarousel" data-bs-slide="prev">
                            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                            <span class="visually-hidden">Previous</span>
                        </button>
                        <button class="carousel-control-next rounded" type="button" data-bs-target="#segmentsCarousel" data-bs-slide="next">
                            <span class="carousel-control-next-icon" aria-hidden="true"></span>
                            <span class="visually-hidden">Next</span>
                        </button>
                    @endif
                </div>    
            @else
                <div class="w-100 text-center p-5">
                    <p>No segments available for this event.</p>
                </div>
            @endif
            <br>
        </div>
        
        <div class="col-lg-4">
            <span class="text" style="font-size: 24px;">Photos</span>
            <div class="row mx-3 mt-2 flex-row p-0 flex-nowrap overflow-auto wrapper">
                @if ($images->isNotEmpty())
                    @foreach ($images as $image)
                        <img
                            src="{{ asset($image->path) }}"
                            style="object-fit: cover; width: 100%; cursor: pointer;"
                            alt="Event Photo"
                            class="p-0 m-0 rounded img-fluid me-3 preview-image"
                            data-bs-toggle="modal"
                            data-bs-target="#imagePreviewModal"
                            data-image-path="{{ asset($image->path) }}"
                        >
                    @endforeach
                @else
                    <div class="w-100 text-center p-5">
                        <p>No images available for this event.</p>
                    </div>
                @endif

                <!-- Modal for Image Preview -->
                <div class="modal fade" id="imagePreviewModal" tabindex="-1" aria-labelledby="imagePreviewModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <img id="previewImage" class="w-100 rounded" alt="Event Photo">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <span class="text" style="font-size: 24px;">Event People</span>
            <div class="card mt-2">
                <div class="card-body pb-0">
                    @if ($peoples->isNotEmpty())
                        @foreach ($peoples as $person)
                        <!-- Person Item -->
                        <div class="row d-flex mb-3 justify-content-between">
                            <div class="col-auto">
                                <div class="person-item" data-person-id="{{ $person->id }}">
                                    <div class="row m-0 p-0 text-capitalize">{{ $person->name . ' | ' . $person->role}}</div>
                                    @if ($person->title)
                                    <div class="row small text-secondary m-0 p-0">Academic/Professional Title: {{ $person->title }}</div>
                                    @else
                                    <div class="row small text-secondary m-0 p-0">N/A</div>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <!-- Person Item End -->
                        @endforeach
                    @else
                        <div class="w-100 text-center py-2">
                            <p>No event person added for this event.</p>
                        </div>
                    @endif
                </div>
            </div>
            <hr class="mt-3 mb-2">
            <p>Last Updated at {{ Carbon::parse($event->updated_at)->format('M d, Y') }}</p>
        </div>

        <div class="col-lg-8">
            @if (!($event->status == 'Planning' || $event->status == 'Done'))
                <span class="text" style="font-size: 24px;">Related Upcoming Events</span>
                <div class="container-fluid mt-2 mb-3">
                    <div class="row flex-row flex-nowrap overflow-auto wrapper">
                        @if ($relatedEvents->isNotEmpty())
                            @foreach($relatedEvents as $relatedEvent)
                            <!-- event card item  -->
                            <a href="{{  route('preview', ['eventId' => $relatedEvent->id]) }}" class="card-link col-sm-6 text-dark" style="text-decoration: none;">
                                <div>
                                    @if ($relatedEvent->cover_photo)
                                    <img class="card-img-top rounded" style="object-fit: cover; height: 190px;" src="{{ asset($relatedEvent->cover_photo) }}" alt="Card image cap">
                                    @else
                                    <img class="card-img-top rounded" style="object-fit: cover; height: 190px;" src="https://developers.elementor.com/docs/assets/img/elementor-placeholder-image.png" alt="Card image cap">
                                    @endif
                                    <div class="p-0 pt-2">
                                        <div class="container">
                                            <div class="row">
                                                <div class="col-auto p-0">
                                                    @if ($relatedEvent->creator->profile_picture)
                                                    <img src="{{ asset($relatedEvent->creator->profile_picture) }}" alt="Avatar" class="rounded-circle mt-1" style="width: 40px; height: 40px">
                                                    @else
                                                    <img src="{{ asset('asset/blank_profile.jpg') }}" alt="Avatar" class="rounded-circle" style="width: 40px; height: 40px">
                                                    @endif
                                                </div>
                                                <div class="col-6">
                                                    <p class="small text-truncate m-0"><strong>{{ $relatedEvent->title }}</strong></p>
                                                    @if ($relatedEvent->description)
                                                    <p class="small text-truncate m-0">{{ $relatedEvent->description }}</p>
                                                    @else
                                                    <p class="small text-truncate text-secondary m-0">No Description</p>
                                                    @endif
                                                </div>
                                                <div class="col-auto fw-bold small pe-0">
                                                    <i class="fa-regular fa-heart"></i> {{ $relatedEvent->hearts }}
                                                    <i class="fa-regular ms-2 fa-circle-user"></i> {{ $relatedEvent->register }}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </a>
                            <!-- event card item end -->
                            @endforeach
                        @else
                            <div class="w-100 text-center py-2 px-5">
                                <p>No related events found.</p>
                            </div>
                        @endif
                    </div>
                </div>
            @endif
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
@endsection

@section('script')
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>
<script src="https://html2canvas.hertzen.com/dist/html2canvas.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/FileSaver.js/2.0.5/FileSaver.min.js"></script>

<script>
    const qrImage = document.getElementById("qrImage");
    const downloadQRBtn = document.getElementById("downloadQRBtn");

    downloadQRBtn.addEventListener("click", async () => {
        const response = await fetch(qrImage.src);
        const blob = await response.blob();
        const downloadLink = document.createElement("a");
        downloadLink.href = URL.createObjectURL(blob);
        downloadLink.download = "{{ $event->title }}_QR_Pass.jpg";
        downloadLink.click();
    });
</script>
<script>
    var assetUrl = "{{ asset('') }}";
    $(document).ready(function() {
        // Preview Images
        $('.preview-image').on('click', function () {
            var imagePath = $(this).data('image-path');
            
            $('#previewImage').attr('src', imagePath);
        });

        // Register Event
        $('#registerBtn').on('click', function() {
            $(this).prop('disabled', true);

            $.ajax({
                url: "{{ route('preview.register') }}",
                type: "POST",
                data: $('#registrationForm').serialize(),
                success: function(response) {
                    if (response.data) {
                        $('#qrImage').attr('src', 'https://api.qrserver.com/v1/create-qr-code/?size=200x200&data=' + response.data);
                    } else {
                        Swal.fire({
                            title: 'Error',
                            text: 'Failed to generate QR code.',
                            icon: 'error',
                            confirmButtonColor: '#3085d6',
                            confirmButtonText: 'OK',
                        });
                    }

                    if(response.success){
                        $('#showRegisteredBtn').removeClass('d-none');
                        $('#showRegisterBtn').addClass('d-none');
                        Swal.fire({
                            title: 'Registered',
                            text: response.success,
                            icon: 'success',
                            showCancelButton: true,
                            confirmButtonColor: '#3085d6',
                            confirmButtonText: 'View QR Pass',
                            cancelButtonText: 'Close',
                        }).then((result) => {
                            if (result.isConfirmed) {
                                $('#registerModal').modal('hide');
                                $('#qrModal').modal('show');
                            }
                        });
                    } else if(response.success){
                        Swal.fire({
                            title: 'Oops',
                            text: response.error,
                            icon: 'error',
                            confirmButtonColor: '#3085d6',
                            confirmButtonText: 'OK'
                        })
                    }
                    $('#registerBtn').prop('disabled', false);
                },
                error: function(error) {
                    console.error(error);
                    alert('Error occurred. Please try again.');
                    $('#registerBtn').prop('disabled', false);
                }
            });
        });
    });
</script>
@endsection
