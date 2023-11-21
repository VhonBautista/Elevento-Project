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
<nav class="navbar navbar-expand-lg bg-primary px-2 py-1">
    <div class="container-fluid">
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav mb-2 mb-lg-0 d-flex justify-content-between w-100">
                <li class="nav-item d-flex justify-content-start">
                    <ul class="navbar-nav mb-2 mb-lg-0">
                        <li class="nav-item">
                            <a class="nav-link text-light active fw-bold" aria-current="page" href="{{ route('plan', ['eventId' => $event->id]) }}">Plan</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-light" href="{{ route('analytics', ['eventId' => $event->id]) }}">Analytics</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-light" href="{{ route('attendance', ['eventId' => $event->id]) }}">Attendance</a>
                        </li>
                    </ul>
                </li>
                <li class="nav-item d-flex align-items-center justify-content-center" style="margin-right: 20%;">
                    <span class="text-white">{{ $event->title }}</span>
                </li>
                <li class="nav-item d-flex justify-content-end align-items-center">
                    <img src="{{ asset($user->profile_picture) }}" alt="Avatar" style="height: 36.5px; width: 36.5px; z-index: 99; margin-right: -7px;" class="rounded-circle">
                    {{-- display 5 users that is in this project --}}
                    <img src="https://images.nationalgeographic.org/image/upload/v1638892272/EducationHub/photos/hoh-river-valley.jpg" alt="Avatar" style="height: 36.5px; width: 36.5px; z-index: 2; margin-right: -7px;" class="rounded-circle">
                    <img src="https://images.nationalgeographic.org/image/upload/v1638892272/EducationHub/photos/hoh-river-valley.jpg" alt="Avatar" style="height: 36.5px; width: 36.5px; z-index: 1; margin-right: -7px;" class="rounded-circle">

                    @if($project->role == 'creator')
                    <div class="dropdown">
                        <button class="btn text-light rounded-4 dropdown-toggle" style="background-color: #4AA2FA;" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fas fa-plus"></i>
                        </button>
                        <div class="dropdown-menu dropdown-menu-end" style="max-height: 410px; overflow-y: auto;">
                            <div class="mx-2 mb-2 input-group" style="font-size: 16px; width: 320px;">
                                <input type="text" class="form-control" name="search_user" id="search-user" placeholder="Search email" style="border-right: none;">
                                <span class="input-group-text"><i class="fa-solid fa-magnifying-glass"></i></span>
                            </div>
                            <hr class="m-0">
                            {{-- display members first then update when someone is being searched --}}
                            <div id="display-users" class="p-3 text-center">
                                Search for users to include in this project
                            </div>
                        </div>
                    </div>
                    @endif
                </li>
            </ul>
        </div>
    </div>
</nav>
<div class="cover">
    @if ($event->cover_photo)
    <img src="{{ asset($event->cover_photo) }}" alt="Your Image">
    @else
    <img src="https://developers.elementor.com/docs/assets/img/elementor-placeholder-image.png" alt="Your Image">
    @endif
    <button class="floating-button me-3">Edit Cover Photo</button>
</div>
<div class="container mt-3">
    <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link dash-tab active" id="details-tab" data-bs-toggle="pill" data-bs-target="#details-tab-content" type="button" role="tab" aria-controls="details-tab-content" aria-selected="true">Event Details</button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link dash-tab" id="segments-tab" data-bs-toggle="pill" data-bs-target="#segments-tab-content" type="button" role="tab" aria-controls="segments-tab-content" aria-selected="false">Segments</button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link dash-tab" id="logs-tab" data-bs-toggle="pill" data-bs-target="#logs-tab-content" type="button" role="tab" aria-controls="logs-tab-content" aria-selected="false">Logs</button>
        </li>
    </ul>
    <hr>
    <div class="tab-content mb-3" id="pills-tabContent">
        <div class="tab-pane fade show active" id="details-tab-content" role="tabpanel" aria-labelledby="details-tab-content">
            <div class="row">
                @if($project->role == 'creator') 
                <div class="col-lg-8 mb-4">
                @else
                <div class="col-lg-12 mb-4">
                @endif
                    <span class="text" style="font-size: 24px;">Event Details</span>
                    <div class="row ms-4 w-100 mt-2">
                        <div class="col-md-4">
                            <p class="text-capitalize"><strong>Event type:</strong> &nbsp; {{ $event->event_type }}</p>
                            <p class="text-capitalize"><strong>Target Audience:</strong> &nbsp; {{ $event->target_audience }}</p>
                        </div>
                        <div class="col-md-8">
                            <p><strong>Start and End Date:</strong> &nbsp; {{ Carbon::parse($event->start_date)->format('M d') }} - {{ Carbon::parse($event->end_date)->subDay()->format('M d') }}</p>
                            <p class="text-capitalize"><strong>Main Venue:</strong> &nbsp; {{ $event->venue->venue_name }} </p>
                        </div>
                    </div>
                    <hr class="mt-2">
                    <span class="text" style="font-size: 24px;">Description</span>
                    <div class="row ms-4 mt-2"> 
                        <div class="col-md-12" id="displayDescription" data-full-description="{{ $event->description }}">
                            @if($event->description)
                                {{ $event->description }}
                            @else
                                Please provide a description before activating your event.
                            @endif
                        </div>
                        <form action="{{ route('update.description', $event->id) }}" method="POST" id="editDescriptionForm" style="display:none;">
                            @csrf
                            @method('PUT')
                            
                            <textarea name="description" class="form-control" id="description" rows="3">{{ $event->description }}</textarea>
                            <button type="submit" class="btn btn-primary mt-2">Save</button>
                        </form>
                        @if($event->status == 'Planning')
                        <button class="mt-2 btn btn-primary text-center" style="width: 150px;" id="editDescriptionBtn">@if($event->description) Edit @else Add @endif Description </button>
                        @endif
                    </div>
                </div>
                @if($project->role == 'creator') 
                <div class="col-lg-4">
                    <div class="row">
                        <div class="col-md-12">
                            <small class="form-text fw-bold">Event Status: {{ $event->status }}</small><br>
                            <form action="{{ route('update.status', $event->id) }}" method="POST" id="updateStatusForm">
                                @csrf
                                @method('PUT')
                                
                                @if($event->status == 'Planning')
                                <button type="submit" class="updateEventStatusBtn btn btn-dark w-100 my-2">Make Event Active</button>                                    
                                @elseif($event->status == 'Active')
                                <button type="submit" class="updateEventStatusBtn btn btn-dark w-100 my-2">Edit Event Details</button>
                                @endif
                            </form>
                            <small class="form-text text-muted">Note: Activating the event indicates that the planning phase has been completed and that no user can edit the event unless it is set back to the planning phase. Additionally, activating the event will display it as active.</small>
                            <hr class="mt-3 mb-1">
                        </div>
                        @if($event->status == 'Planning')
                        <div class="col-md-12 mt-3">
                            <button class="btn btn-dark w-100 mb-3 p-2" data-bs-toggle="modal" data-bs-target="#rescheduleModal">Reschedule Event</button>
                            <small class="form-text text-muted">Note: Rescheduling an event requires approval from the admin and may take some time. Please provide a reason for rescheduling the event</small>

                            <div class="modal fade" id="rescheduleModal" tabindex="-1" aria-labelledby="rescheduleModal" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h1 class="modal-title fs-5 fw-bold" id="rescheduleModal">Reschedule Event</h1>
                                            <button type="button" class="btn-close" id="reschedule-btn" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <small class="form-text text-muted">Note: Please set the end date to be at least one day after the actual end date of the event. For example, if the event starts on July 20, set the end date to July 21 or later to ensure accurate scheduling.</small>

                                            <form id="reschedule-event-form" class="row px-2 g-3 pt-2">
                                                @csrf

                                                <input type="hidden" name="event_id" value={{ $event->id }}>

                                                <div class="col-md-6">
                                                    <label class="form-label">Start</label>
                                                    <input type="date" value="{{ \Carbon\Carbon::parse($event->start_date)->format('Y-m-d') }}" class="form-control" id="event-start" name="event_start">
                                                    <div id="event-start-error" class="form-error mt-2 text-danger small d-none"></div>
                                                </div>

                                                <div class="col-md-6">
                                                    <label for="event-end" class="form-label">End</label>
                                                    <input type="date" value="{{ \Carbon\Carbon::parse($event->end_date)->format('Y-m-d') }}" class="form-control" id="event-end" name="event_end">
                                                    <div id="event-end-error" class="form-error mt-2 text-danger small d-none"></div>
                                                </div>

                                                <div class="col-md-12">
                                                    <div id="event-date-error" style="margin-top: -12px;" class="form-error text-danger small d-none"></div>
                                                </div>

                                                <div class="col-md-12">
                                                    <label for="reason" class="form-label">Reason</label>
                                                    <textarea name="reason" class="form-control" id="reason" placeholder="Please provide the reason for rescheduling the event" rows="3"></textarea>
                                                    
                                                    <div id="reason-error" class="form-error mt-2 text-danger small d-none"></div>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="submit" class="btn btn-primary">Request New Schedule</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <hr class="mt-3 mb-1">
                        </div>
                        @endif
                        <div class="col-md-12 mt-3">
                            @if($project->role == 'creator') 
                                <form action="{{ route('delete.event') }}" method="POST">
                                    @csrf

                                    <input type="hidden" name="event_id" value="{{ $event->id }}">

                                    <button class="btn btn-danger w-100 mb-3 p-2" id="deleteEventBtn" type="submit">Delete Event</button>
                                    <small class="form-text text-muted">Note: Deleting the event will permanently remove all associated records and data related to this event.</small>
                                </form>
                                <hr class="mt-3 mb-1">
                            @endif
                        </div>
                        <div class="col-12">
                            <!-- <span class="text" style="font-size: 24px;">Photos</span>
                            <div class="row flex-row flex-nowrap overflow-auto wrapper">
                                display photos where event_id == to this.event_id
                                <img src="https://images.nationalgeographic.org/image/upload/v1638892272/EducationHub/photos/hoh-river-valley.jpg" style="object-fit: coevr; width: 100%;" alt="Event Photo" class="img-fluid mb-3">    
                                <img src="https://images.nationalgeographic.org/image/upload/v1638892272/EducationHub/photos/hoh-river-valley.jpg" style="object-fit: coevr; width: 100%;" alt="Event Photo" class="img-fluid mb-3">    
                                <img src="https://images.nationalgeographic.org/image/upload/v1638892272/EducationHub/photos/hoh-river-valley.jpg" style="object-fit: coevr; width: 100%;" alt="Event Photo" class="img-fluid mb-3">    
                                <img src="https://images.nationalgeographic.org/image/upload/v1638892272/EducationHub/photos/hoh-river-valley.jpg" style="object-fit: coevr; width: 100%;" alt="Event Photo" class="img-fluid mb-3">    
                            </div>
                            <div class="d-flex justify-content-center">
                                <button class="btn btn-primary w-100"><i class="fas fa-plus"></i> Add Photo</button>
                            </div>
                            <hr class="mb-3">
                            <span class="text" style="font-size: 24px;">Share</span>
                            <div class="d-flex g-2 w-100">
                                <a href="#"><i class="fab fa-facebook fa-2x text-primary me-2 ms-4"></i></a>
                                <a href="#"><i class="fab fa-instagram fa-2x text-danger"></i></a>
                            </div>
                            <hr> -->
                            <p>Last Updated 4 days ago</p>
                        </div>
                    </div>
                </div>
                @endif
            </div>
        </div>

        <div class="tab-pane fade" id="segments-tab-content" role="tabpanel" aria-labelledby="segments-tab-content">
            <div class="row">

                <div class="col-md-8">
                    <span class="text" style="font-size: 24px;">Segments</span>
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @elseif(session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif
                    <div id="segments-container">
                        @if ($segments->isNotEmpty())
                            @foreach ($segments as $segment)
                                <!-- Sub-Events Item -->
                                <div class="card mb-3">
                                    <div class="card-header bg-dark">
                                        <p class="card-title m-0 text-light" style="font-size: 14px;">{{ Carbon::parse($segment->date)->format('F j, Y (l)') }}</p>
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
                                                <div class="card mb-3">
                                                    <div class="card-header d-flex justify-content-between align-items-center">
                                                        <h5 class="card-title m-0">{{ $flow->timeline . ' (' . \Carbon\Carbon::parse($flow->start_time)->format('g:i A') . ' - ' . \Carbon\Carbon::parse($flow->end_time)->format('g:i A') . ')' }}</h5>
                                                        @if($event->status == 'Planning')
                                                        <div class="row btn-rows" data-flow-id="{{ $flow->id }}">
                                                            <div class="col-auto pe-0">
                                                                <button class="edit-flow-btn btn btn-primary" data-flow-id="{{ $flow->id }}"><i class="fa-solid fa-pen-to-square"></i></button>
                                                            </div>
                                                            <div class="col-auto">
                                                                <form action="{{ route('event.delete-flow', ['eventId' => $event->id, 'segmentId' => $segment->id, 'flowId' => $flow->id]) }}" method="POST">
                                                                    @csrf
                                                                    @method('DELETE')
                                                                    <button type="submit" class="delete-flow-btn btn btn-danger"><i class="fa-solid fa-trash"></i></button>
                                                                </form>
                                                            </div>
                                                        </div>
                                                        @endif
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
                                    @if($event->status == 'Planning')
                                    <div class="card-footer">
                                        <div class="d-flex justify-content-center">
                                            <button class="btn btn-primary w-100" data-id="{{ $segment->id }}" data-id1="{{ $event->id }}" data-bs-toggle="modal" data-bs-target="#flowModal">
                                                <i class="fas fa-plus"></i> Create Flow
                                            </button>
                                        </div>
                                    </div>
                                    @endif
                                </div>
                            @endforeach
                            <!-- Sub-Events Item End -->
                            
                            <!-- Flow Modal -->
                            <div class="modal fade" id="flowModal" tabindex="-1" aria-labelledby="flowModal" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h1 class="modal-title fs-5 fw-bold" id="flowModal">Add New Flow</h1>
                                            <button type="button" class="btn-close" id="reschedule-btn" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <form id="flow-event-form" action="{{ route('event.add-flow') }}" method="POST" class="row px-2 g-3 pt-2">
                                                @csrf
    
                                                <input type="hidden" name="event_id">

                                                <input type="hidden" name="segment_id">
    
                                                <div class="col-md-12">
                                                    <label class="form-label">Time of Day</label>
                                                    <select name="time_of_day" id="time_of_day" class="form-control">
                                                        <option value="Morning">Morning</option>
                                                        <option value="Noon">Noon</option>
                                                        <option value="Afternoon">Afternoon</option>
                                                        <option value="Evening">Evening</option>
                                                        <option value="Midnight">Midnight</option>
                                                    </select>                                                
                                                </div>
    
                                                <div class="col-md-6">
                                                    <label for="time-start" class="form-label">Start Time</label>
                                                    <input type="time" class="form-control" id="time-start" value="08:00" name="time_start">
                                                    <div id="time-start-error" class="form-error mt-2 text-danger small d-none"></div>
                                                </div>
    
                                                <div class="col-md-6">
                                                    <label for="time-end" class="form-label">End Time</label>
                                                    <input type="time" class="form-control" id="time-end" value="12:00" name="time_end">
                                                    <div id="time-end-error" class="form-error mt-2 text-danger small d-none"></div>
                                                </div>
    
                                                <div class="col-md-12" style="margin-top: 2px;">
                                                    <small class="form-text text-muted">Set the time range from the start to the end of the flow.</small>
                                                    <div id="event-time-error" style="margin-top: 2px;" class="form-error text-danger small d-none"></div>
                                                </div>
    
                                                <div class="col-md-12">
                                                    <label for="flow" class="form-label">Program Flow</label>
                                                    <textarea name="flow" class="form-control" id="flow" placeholder="Registration 8:00 AM, Opening 9:00 AM, and so on." rows="3"></textarea>
                                                    <small class="form-text text-muted">Note: Please separate each flow with a comma (','). For example: Registration 8:00 AM, Opening 9:00 AM, and so on.</small>
                                                    
                                                    <div id="flow-error" class="form-error mt-2 text-danger small d-none"></div>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="submit" class="btn btn-primary" id="submitFlowForm">Add Flow</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @else
                            <div class="w-100 text-center p-5">
                                <p>No segments available for this event.</p>
                            </div>
                        @endif
                    </div>
                </div>
                
                <div class="col-md-4">
                    <span class="text" style="font-size: 24px;">Event People & Guests</span>
                    @if(session('person-success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('person-success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @elseif(session('person-error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            {{ session('person-error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif
                    <div class="card">
                        <div class="card-body pb-0">
                            @if ($peoples->isNotEmpty())
                                @foreach ($peoples as $person)
                                <!-- Person Item -->
                                <div class="row d-flex mb-3 justify-content-between">
                                    <div class="col-auto">
                                        <div class="person-item" data-person-id="{{ $person->id }}">
                                            <div class="row m-0 p-0 text-capitalize">{{ $person->name . ' (' . $person->role . ')'}}</div>
                                            @if ($person->title)
                                            <div class="row small text-secondary m-0 p-0">{{ $person->title }}</div>
                                            @else
                                            <div class="row small text-secondary m-0 p-0">N/A</div>
                                            @endif
                                        </div>
                                        <form class="w-100 row g-1 d-none edit-person-form" data-person-id="{{ $person->id }}">
                                            @csrf

                                            <input type="hidden" name="event_id" value="{{ $event->id }}">
                                            <input type="hidden" name="person_id" value="{{ $person->id }}">

                                            <div class="col-md-6">
                                                <input type="text" class="form-control" id="edit-person-name" placeholder="Enter person's name" required value="{{ $person->name }}" name="name">
                                                <div id="edit-person-name-error" class="form-error mt-2 text-danger small d-none"></div>
                                            </div>

                                            <div class="col-md-6">
                                                <select name="role" id="edit-person-role" class="form-control">
                                                    <option value="mc" @if($person->role == 'mc') selected @endif>MC</option>
                                                    <option value="speaker" @if($person->role == 'speaker') selected @endif>Speaker</option>
                                                    <option value="guest" @if($person->role == 'guest') selected @endif>Guest</option>
                                                    <option value="technical" @if($person->role == 'technical') selected @endif>Technical</option>
                                                    <option value="logistics" @if($person->role == 'logistics') selected @endif>Logistics</option>
                                                    <option value="sponsor" @if($person->role == 'sponsor') selected @endif>Sponsor</option>
                                                </select>                                         
                                            </div>

                                            <div class="col-md-12 my-1">
                                                <input type="text" class="form-control" id="edit-person-title" placeholder="Enter academic or professional title, e.g., PhD, Doctorate, etc." value="{{ $person->title }}" name="title">
                                                <small class="form-text text-muted">This field is optional and not required.</small>
                                            </div>

                                            <div class="w-100 d-flex mt-2 justify-content-end">
                                                <button type="submit" class="mx-1 btn btn-primary">Save</button>
                                                <button type="button" class="cancel-person-btn mx-1 btn btn-secondary" data-person-id="{{ $person->id }}">Cancel</button>
                                            </div>
                                        </form>
                                    </div>
                                    <div class="col-auto d-flex align-items-center justify-content-end">
                                        @if($event->status == 'Planning')
                                        <div class="row btn-person-rows" data-person-id="{{ $person->id }}">
                                            <div class="col-auto pe-0">
                                                <button class="edit-person-btn btn btn-primary" data-person-id="{{ $person->id }}"><i class="fa-solid fa-pen-to-square"></i></button>
                                            </div>
                                            <div class="col-auto">
                                                <form action="{{ route('event.delete-person', ['eventId' => $event->id, 'personId' => $person->id]) }}" method="POST">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="delete-person-btn btn btn-danger"><i class="fa-solid fa-trash"></i></button>
                                                </form>
                                            </div>
                                        </div>
                                        @endif
                                    </div>
                                </div>
                                <hr>
                                <!-- Person Item End -->
                                @endforeach
                            @else
                                <div class="w-100 text-center py-2">
                                    <p>No event person added for this event.</p>
                                </div>
                            @endif
                        </div>
                        @if($event->status == 'Planning')
                        <div class="card-footer">
                            <div class="d-flex justify-content-center">
                                <button class="btn btn-primary w-100" data-bs-toggle="modal" data-bs-target="#personModal">
                                    <i class="fas fa-plus"></i> Add Event Person
                                </button>

                                <!-- Person Modal -->
                                <div class="modal fade" id="personModal" tabindex="-1" aria-labelledby="personModal" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h1 class="modal-title fs-5 fw-bold" id="personModal">Add New Event Person</h1>
                                                <button type="button" class="btn-close" id="reschedule-btn" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <form id="person-event-form" action="{{ route('event.add-person') }}" method="POST" class="row px-2 g-3 pt-2">
                                                    @csrf
        
                                                    <input type="hidden" name="event_id" value="{{ $event->id }}">
        
                                                    <div class="col-md-6">
                                                        <label for="person-name" class="form-label">Name</label>
                                                        <input type="text" class="form-control" id="person-name" placeholder="Enter person's name" required name="name">
                                                        <div id="person-name-error" class="form-error mt-2 text-danger small d-none"></div>
                                                    </div>
        
                                                    <div class="col-md-6">
                                                        <label class="form-label">Role</label>
                                                        <select name="role" id="person-role" class="form-control">
                                                            <option value="" selected>Select Role</option>
                                                            <option value="mc">MC</option>
                                                            <option value="speaker">Speaker</option>
                                                            <option value="guest">Guest</option>
                                                            <option value="technical">Technical</option>
                                                            <option value="logistics">Logistics</option>
                                                            <option value="sponsor">Sponsor</option>
                                                        </select>
                                                        <div id="person-role-error" class="form-error mt-2 text-danger small d-none"></div>                                             
                                                    </div>

                                                    <div class="col-md-12">
                                                        <label for="person-title" class="form-label">Academic Title</label>
                                                        <input type="text" class="form-control" id="person-title" placeholder="Enter academic or professional title, e.g., PhD, Doctorate, etc." name="title">
                                                        <small class="form-text text-muted">This field is optional and not required.</small>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="submit" class="btn btn-primary" id="submitPersonForm">Add Person</button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <div class="tab-pane fade" id="logs-tab-content" role="tabpanel" aria-labelledby="logs-tab-content">
            <span class="text" style="font-size: 24px;">Event Logs</span>
            @if (!$projectLogs->isEmpty())
            <table class="table">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Action</th>
                        <th>Description</th>
                        <th>Who</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($projectLogs as $projectLog)  
                    <tr>
                        <td class="text-capitalize">{{ $projectLog->created_at->format('F j, Y') }}</td>
                        <td class="text-capitalize">{{ $projectLog->status }}</td>
                        <td class="text-capitalize">{{ $projectLog->description }}</td>
                        <td class="text-capitalize">{{ $projectLog->user->username }}</td>
                    </tr>
                    @endforeach
                    <!-- Add more rows as needed -->
                </tbody>
            </table>
            {{ $projectLogs->links() }}
            @else
            <div class="container w-100 text-center mt-5">
                No logs found
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
<script>
    $(document).ready(function() {
        // cookies
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

        // -------------------------------------------
        var displayDescription = $('#displayDescription');

        // Edit description toggle
        $("#editDescriptionBtn").on("click", function(){
            $("#displayDescription").toggle();
            $("#editDescriptionForm").toggle();
            $(this).toggle();
            $("#description").focus();
        });

        // Edit description form submission
        $("#editDescriptionForm").submit(function(e){
            e.preventDefault();

            var formData = $(this).serialize();

            $.ajax({
                url: $(this).attr('action'),
                type: 'PUT',
                data: formData,
                success: function(response){
                    var updatedDescription = response.description;

                    $("#displayDescription").text(updatedDescription);

                    $("#displayDescription").toggle();
                    $("#editDescriptionForm").toggle();
                    $("#editDescriptionBtn").text('Edit Description');
                    $("#editDescriptionBtn").toggle();
                },
                error: function(error){
                    alert('error');
                }
            });
        });
        
        // -------------------------------------------
        // create flow form
        $('.btn[data-bs-target="#flowModal"]').click(function () {
            var eventId = $(this).data('id1');
            var segmentId = $(this).data('id');
            $('#flow-event-form input[name="event_id"]').val(eventId);
            $('#flow-event-form input[name="segment_id"]').val(segmentId);
        });

        $('#submitFlowForm').click(function () {
            event.preventDefault();

            var timeStart = $('#time-start').val();
            var timeEnd = $('#time-end').val();
            var flow = $('#flow').val();
            var eventId = $('#flow-event-form input[name="event_id"]').val();
            var segmentId = $('#flow-event-form input[name="segment_id"]').val();

            $('.form-error').text('').addClass('d-none');

            if (!timeStart) {
                $('#time-start').addClass('is-invalid');
                $('#time-start-error').text('Event start time is a required field.').removeClass('d-none');
                return;
            }
            $('#time-start').removeClass('is-invalid');

            if (!timeEnd) {
                $('#time-end').addClass('is-invalid');
                $('#time-end-error').text('Event end time is a required field.').removeClass('d-none');
                return;
            }
            $('#time-end').removeClass('is-invalid');

            if (timeEnd <= timeStart) {
                $('#time-end').addClass('is-invalid');
                $('#event-time-error').text('End time should be after the start time.').removeClass('d-none');
                return;
            }
            $('#time-end').removeClass('is-invalid');

            if (!flow) {
                $('#flow').addClass('is-invalid');
                $('#flow-error').text('Program flow is a required field.').removeClass('d-none');
                return;
            }
            $('#flow').removeClass('is-invalid');

            var formData = $('#flow-event-form').serialize();

            $.ajax({
                type: 'POST',
                url: "{{ route('event.add-flow') }}",
                data: formData,
                success: function (response) {
                    if (response.success){
                        Swal.fire({
                            title: 'Flow Created',
                            text: 'Flow has been created successfully.',
                            icon: 'success',
                            confirmButtonText: 'OK'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                location.reload();
                            }
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
                error: function (xhr, status, error) {
                    console.error(xhr.responseText);
                }
            });
        });

        // edit flow 
        $('.edit-flow-btn').click(function() {
            var flowId = $(this).data('flow-id');

            $('.btn-rows[data-flow-id="' + flowId + '"]').hide();
            $('.flow-item[data-flow-id="' + flowId + '"]').hide();

            $('.edit-flow-form[data-flow-id="' + flowId + '"]').removeClass('d-none');
        });

        $('.cancel-edit-btn').click(function() {
            var flowId = $(this).data('flow-id');

            $('.btn-rows[data-flow-id="' + flowId + '"]').show();
            $('.flow-item[data-flow-id="' + flowId + '"]').show();

            $('.edit-flow-form[data-flow-id="' + flowId + '"]').addClass('d-none');
        });

        $('.edit-flow-form').submit(function(e) {
            e.preventDefault();
            var flowId = $(this).data('flow-id');
            var formData = $(this).serialize();

            $.ajax({
                type: 'POST',
                url: "{{ route('event.update-flow') }}",
                data: formData,
                success: function(response) {
                    if (response.success){
                        Swal.fire({
                            title: 'Flow Updated',
                            text: 'Flow has been updated successfully.',
                            icon: 'success',
                            confirmButtonText: 'OK'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                location.reload();
                            }
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
            });
        });

        // delete flow 
        $('.delete-flow-btn').click(function(e) {
            e.preventDefault(); 

            Swal.fire({
                title: 'Are you sure?',
                text: 'You won\'t be able to revert this!',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                confirmButtonText: 'Delete'
            }).then((result) => {
                if (result.isConfirmed) {
                    $(this).closest('form').submit();
                }
            });
        });

        // -------------------------------------------
        // add event person
        $('#submitPersonForm').click(function () {
            event.preventDefault();

            var name = $('#person-name').val();
            var role = $('#person-role').val();

            $('.form-error').text('').addClass('d-none');

            if (!name) {
                $('#person-name').addClass('is-invalid');
                $('#person-name-error').text('Event start time is a required field.').removeClass('d-none');
                return;
            }
            $('#person-name').removeClass('is-invalid');

            if (!role) {
                $('#person-role').addClass('is-invalid');
                $('#person-role-error').text('Event end time is a required field.').removeClass('d-none');
                return;
            }
            $('#person-role').removeClass('is-invalid');

            var formData = $('#person-event-form').serialize();

            $.ajax({
                type: 'POST',
                url: "{{ route('event.add-person') }}",
                data: formData,
                success: function (response) {
                    if (response.success){
                        Swal.fire({
                            title: 'Event Person Added',
                            text: 'Event person has been added to the event successfully.',
                            icon: 'success',
                            confirmButtonText: 'OK'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                location.reload();
                            }
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
                error: function (xhr, status, error) {
                    console.error(xhr.responseText);
                }
            });
        });

        // edit person 
        $('.edit-person-btn').click(function() {
            var perosnId = $(this).data('person-id');

            $('.btn-person-rows[data-person-id="' + perosnId + '"]').hide();
            $('.person-item[data-person-id="' + perosnId + '"]').hide();

            $('.edit-person-form[data-person-id="' + perosnId + '"]').removeClass('d-none');
        });

        $('.cancel-person-btn').click(function() {
            var personId = $(this).data('person-id');

            $('.btn-person-rows[data-person-id="' + personId + '"]').show();
            $('.person-item[data-person-id="' + personId + '"]').show();

            $('.edit-person-form[data-person-id="' + personId + '"]').addClass('d-none');
        });

        $('.edit-person-form').submit(function(e) {
            e.preventDefault();
            var formData = $(this).serialize();

            $.ajax({
                type: 'POST',
                url: "{{ route('event.update-person') }}",
                data: formData,
                success: function(response) {
                    if (response.success){
                        Swal.fire({
                            title: 'Event Person Updated',
                            text: 'Event person has been updated successfully.',
                            icon: 'success',
                            confirmButtonText: 'OK'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                location.reload();
                            }
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
            });
        });

        // delete person 
        $('.delete-person-btn').click(function(e) {
            e.preventDefault(); 

            Swal.fire({
                title: 'Are you sure?',
                text: 'You won\'t be able to revert this!',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                confirmButtonText: 'Delete'
            }).then((result) => {
                if (result.isConfirmed) {
                    $(this).closest('form').submit();
                }
            });
        });

        // validate desc before activating event
        $('.updateEventStatusBtn').click(function (event) {
            event.preventDefault();

            @if($event->description === null)
                Swal.fire({
                    icon: 'warning',
                    title: 'Oops...',
                    text: 'You need to have a description for this event first',
                    confirmButtonColor: '#3085d6',
                    confirmButtonText: 'OK'
                });
                return;
            @endif

            Swal.fire({
                title: '{{ $event->status === "Planning" ? "Activate Event" : "Edit Event" }}',
                text: '{{ $event->status === "Planning" ? "Once active, you will not be able to edit the event or change the schedule." : "In planning mode, the event will not be shown to the public." }}',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Confirm'
            }).then((result) => {
                if (result.isConfirmed) {
                    $(this).closest('form').submit();
                }
            });
        });

        // delete event
        $('#deleteEventBtn').click(function (event) {
            event.preventDefault();

            Swal.fire({
                title: 'Delete Event',
                text: ' Deleting the event will permanently remove all associated records and data related to this event.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                confirmButtonText: 'Delete'
            }).then((result) => {
                if (result.isConfirmed) {
                    $(this).closest('form').submit();
                }
            });
        });

        // reschedule event
        $('#reschedule-btn').click(function() {
            $('#reschedule-event-form')[0].reset();
        });

        $('#reschedule-event-form').submit(function(event) {
            event.preventDefault();
            
            var eventStart = $('#event-start').val();
            var eventEnd = $('#event-end').val();
            var reason = $('#reason').val();

            $('.form-error').text('').addClass('d-none');

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

            var startDate = new Date(eventStart);
            var endDate = new Date(eventEnd);

            if (endDate <= startDate) {
                $('#event-start').addClass('is-invalid');
                $('#event-end').addClass('is-invalid');
                $('#event-date-error').text('End date should be after the start date.').removeClass('d-none');
                return;
            }

            if (!reason) {
                $('#reason').addClass('is-invalid');
                $('#reason-error').text('You need to provide the reason for rescheduling the event.').removeClass('d-none');
                return;
            }
            $('#reason').removeClass('is-invalid');

            $.ajax({
                type: 'POST',
                url: "{{ route('request.schedule') }}",
                data: $('#reschedule-event-form').serialize(),
                success: function(response) {
                    if (response.success) {
                        $('#rescheduleModal').modal('hide');
                        $('#reschedule-event-form')[0].reset();
                        Swal.fire({
                            icon: 'success',
                            title: 'Request Sent!',
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
    });
</script>
@endsection
