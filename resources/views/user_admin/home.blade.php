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
                <a href="" class="active">
                    <i class="fa-solid fa-gauge"></i>
                    <span class="side-link-name">Dashboard</span>
                </a>
                <span class="tool-tip">Dashboard</span>
            </li>
            <li>
                <a href="">
                    <i class="fa-solid fa-chart-line"></i>
                    <span class="side-link-name">Analytics</span>
                </a>
                <span class="tool-tip">Analytics</span>
            </li>
            <li>
                <a href="">
                    <i class="fa-solid fa-toolbox"></i>
                    <span class="side-link-name">Management</span>
                </a>
                <span class="tool-tip">Management</span>
            </li>
            <li>
                <a href="">
                    <i class="fa-solid fa-users"></i>
                    <span class="side-link-name">Request</span>
                </a>
                <span class="tool-tip">Request</span>
            </li>
            <li>
                <a href="">
                    <i class="fa-solid fa-calendar-days"></i>
                    <span class="side-link-name">Events</span>
                </a>
                <span class="tool-tip">Events</span>
            </li>
            <li>
                <a href="">
                    <i class="fa-solid fa-gear"></i>
                    <span class="side-link-name">Settings</span>
                </a>
                <span class="tool-tip">Settings</span>
            </li>
        </ul>
        <div class="profile-content">
            <div class="profile">
                <div class="profile-details">
                    <img src="{{ asset('asset/flip_front.png') }}" alt="">
                    <div class="name-role">
                        <div class="user-name">{{ ucfirst(Auth::user()->username) }}</div>
                        <div class="user-role">{{ ucfirst(Auth::user()->role) }}</div>
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
                <div class="d-flex align-items-center px-3 m-0">
                    <div class="circular-avatar me-2">
                        <img src="{{ asset('asset/psu_logo.png') }}" alt="Avatar">
                    </div>
                    <div class="row justify-content-center align-items-start">
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
                <li class="nav-item">
                    <button type="button" class="btn rounded-pill btn-primary position-relative">
                        <i class="fa-regular fa-bell"></i>
                        <span class="position-absolute notification start-100 translate-middle badge rounded-pill bg-danger">
                            1
                        </span>
                    </button>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link p-0 px-3 rounded-pill dropdown-toggle d-flex align-items-center" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <div class="circular-avatar me-2">
                            <img src="{{ asset('asset/flip_front.png') }}" alt="Avatar">
                        </div>
                    </a>
    
                    <ul class="dropdown-menu mt-2" aria-labelledby="navbarDropdown">
                        <li><span class="dropdown-item">Hello {{ ucfirst(Auth::user()->username) }}</span></li>
                        <li><a class="dropdown-item" href="#">Settings</a></li>
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

        <nav class="navbar navbar-expand-lg navbar-light bg-light">
            <div class="container-fluid">
                
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse px-4 navbar-collapse justify-content-between" id="navbarSupportedContent">
                    <ul class="navbar-nav">
                        <!-- <li class="nav-item">
                            <a class="nav-link active" aria-current="page" href="#">Home</a>
                        </li>-->
                    </ul>

                    <ul class="navbar-nav px-2">
                        
                    </ul>
                </div>
            </div>
        </nav>
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
