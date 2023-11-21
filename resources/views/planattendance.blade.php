@extends('layouts.app')

@php
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
                            <a class="nav-link text-light" aria-current="page" href="{{ route('admin.plan', ['eventId' => $eventId]) }}">Plan</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-light" href="{{ route('admin.analytics', ['eventId' => $eventId]) }}">Analytics</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-light active fw-bold" href="{{ route('admin.attendance', ['eventId' => $eventId]) }}">Attendance</a>
                        </li>
                    </ul>
                </li>
                <li class="nav-item d-flex align-items-center justify-content-center" style="margin-right: 20%;">
                    <span class="text-white">Music Event</span>
                </li>
                <li class="nav-item d-flex justify-content-end align-items-center">
                    <img src="https://images.nationalgeographic.org/image/upload/v1638892272/EducationHub/photos/hoh-river-valley.jpg" alt="Avatar" style="height: 36.5px; width: 36.5px; z-index: 1; margin-right: -6px;" class="rounded-circle">
                    <button class="btn text-light rounded-circle" style="background-color: #4AA2FA;"><i class="fas fa-plus"></i></button>
                </li>
            </ul>
        </div>
    </div>
</nav>

<div class="container mt-3">
    <div class="row">
        <div class="col-lg-7">
            <span class="text" style="font-size: 24px;">Attendance Tracker</span>
            <div class="row mt-3">
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-body pb-0">
                            <div class="d-flex justify-content-between text-secondary w-100 small align-items-center">
                                <strong>
                                    Total Attendees
                                </strong>
                                <i class="fa-solid fa-user-group"></i>
                            </div>
                            <h2 class="fw-bold mb-2 p-0">0</h2>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-body pb-0">
                            <div class="d-flex justify-content-between text-secondary w-100 small align-items-center">
                                <strong>
                                    Male
                                </strong>
                                <i class="fa-solid fa-user-group"></i>
                            </div>
                            <h2 class="fw-bold mb-2 p-0">0</h2>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-body pb-0">
                            <div class="d-flex justify-content-between text-secondary w-100 small align-items-center">
                                <strong>
                                    Female
                                </strong>
                                <i class="fa-solid fa-user-group"></i>
                            </div>
                            <h2 class="fw-bold mb-2 p-0">0</h2>
                        </div>
                    </div>
                </div>
                <div class="col-md-12 mt-4">
                    <div class="card">
                        <div class="card-body">
                            <span class="text text-dark fw-bold" style="font-size: 16px;">Attendance Graph</span>
                            
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-5">
            <span class="text" style="font-size: 24px;">QR Scanner</span>
            <center>
                <button class="btn btn-primary p-2 w-100">Start Scanning</button>
            </center>
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
<script>
    $(document).ready(function() {
        
    });
</script>
@endsection
