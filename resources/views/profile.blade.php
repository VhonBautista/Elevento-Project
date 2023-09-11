@extends('layouts.app')

@section('content')
@if ( $user->role != "User" )
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
                <a href="">
                    <i class="fa-solid fa-gauge"></i>
                    <span class="side-link-name">Home</span>
                </a>
            </li>
            <li>
                <a href="">
                    <i class="fa-solid fa-toolbox"></i>
                    <span class="side-link-name">Tasks</span>
                </a>
            </li>
            <li>
                <a href="">
                    <i class="fa-solid fa-calendar-days"></i>
                    <span class="side-link-name">Events</span>
                </a>
            </li>
            @else
            <li>
                <a href="{{ route('admin.dashboard') }}">
                    <i class="fa-solid fa-gauge"></i>
                    <span class="side-link-name">Home</span>
                </a>
            </li>
            <li>
                <a href="">
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
            @endif
            <li>
                <a href="{{ route('profile.edit', ['id' => Auth::user()->id]) }}" class="active">
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
        @endif
        @if ( $user->role != 'User' )
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
                            <img src="{{ asset($user->profile_picture) }}" alt="Avatar">
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
        @else
        <nav class="navbar navbar-expand-lg px-5 py-2 bg-primary">
            <div class="container-fluid">
                <a class="navbar-brand" href="#">
                    <img src="{{ asset('asset/elevento_logo_white.png') }}" class="me-1" style="height:28px;">
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarText" aria-controls="navbarText" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarText">
                    <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                        <li class="nav-item">
                        <a class="nav-link active text-light" aria-current="page" href="#">Home</a>
                        </li>
                        <li class="nav-item">
                        <a class="nav-link text-light" href="#">My Events</a>
                        </li>
                        <li class="nav-item">
                        <a class="nav-link text-light" href="#">History</a>
                        </li>
                    </ul>
                    <ul class="navbar-nav mb-2 mb-lg-0 gap-3">
                        <li class="nav-item">
                            <a href="#" class="btn btn-primary rounded-pill" style="border: 2px solid #fff">
                                <i class="fa-solid fa-globe mx-1"></i>
                                Explore Events
                            </a>
                        </li>
                        <li class="nav-item">
                            <button type="button" class="btn btn-primary rounded-pill position-relative" style="border: 2px solid #fff">
                                <i class="fa-regular fa-bell"></i>
                                <span class="position-absolute notification start-100 translate-middle badge rounded-pill bg-danger">
                                    0
                                </span>
                            </button>
                        </li>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle p-0" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <div class="btn btn-primary rounded-pill" style="border: 2px solid #fff">
                                    <img src="{{ asset($user->profile_picture) }}" class="me-1" style="width:22px; height:22px; border-radius: 50%;" alt="Avatar">
                                    {{ ucfirst(Auth::user()->username) }}
                                </div>
                            </a>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="{{ route('profile.edit', ['id' => Auth::user()->id]) }}">Settings</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item" href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Logout</a></li>
                            </ul>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
        @endif

        <div class="container-fluid px-4 p-2">
            <div class="container mt-2">
                <span class="text">Settings</span>
                <div class="row flex-lg-nowrap mt-2">
                    <div class="col">
                        <div class="row">
                            <div class="col mb-3">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="e-profile">

                                            <div class="row">
                                                <div class="col-12 col-sm-auto mb-3">
                                                    <div class="mx-auto" style="width: 140px;">
                                                        @if(Auth::user()->profile_picture == null)
                                                        <div class="d-flex justify-content-center align-items-center rounded-circle" style="height: 140px; background-color: rgb(233, 236, 239);">
                                                            <span style="color: rgb(166, 168, 170); font: bold 8pt Arial;">140x140</span>
                                                        </div>
                                                        @else
                                                        <div class="rounded-circle" style="height: 140px; width: 140px; overflow:hidden; position: relative;">
                                                            <img style="display: block; position: absolute; top: 0; left: 0; width: 100%; height: 100%; object-fit: cover;" src="{{ asset($user->profile_picture) }}" alt="">
                                                        </div>
                                                        @endif
                                                    </div>
                                                </div>
                                                <div class="col d-flex flex-column flex-sm-row justify-content-between mb-3">
                                                    <div class="profile-details-settings text-sm-left mb-2 mb-sm-0">
                                                        <h4 class="pt-sm-2 pb-1 mb-0 text-nowrap">
                                                        @if($user)
                                                        {{ $user->username }}
                                                        @endif
                                                        </h4>
                                                        <p class="mb-0">
                                                        @if($user->campusEntity)
                                                        {{ $user->campusEntity->firstname }}
                                                        {{ $user->campusEntity->middlename }}
                                                        {{ $user->campusEntity->lastname }}
                                                        @endif
                                                        </p>
                                                        <p class="mb-0">{{ $user->user_id }}</p>
                                                        <div class="mt-2">
                                                            <button class="btn btn-primary" type="button" data-bs-toggle="modal" data-bs-target="#changePhotoModal">
                                                                <i class="fa fa-fw fa-camera"></i>
                                                                <span>Change Photo</span>
                                                            </button>
                                                        </div>
                                                    </div>

                                                    <!-- Modal -->
                                                    <div class="modal fade" id="changePhotoModal" tabindex="-1" aria-labelledby="changePhotoModalLabel" aria-hidden="true">
                                                        <div class="modal-dialog">
                                                            <div class="modal-content">
                                                                <div class="modal-header">
                                                                    <h1 class="modal-title fs-5" id="changePhotoModalLabel">Change Photo</h1>
                                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                                </div>
                                                                <form action="{{ route('upload.photo') }}" method="post" enctype="multipart/form-data" class="container">
                                                                    <div class="modal-body">
                                                                        <figure class="image-container rounded-circle" style="height: 140px; width: 140px; overflow:hidden; display: none;">
                                                                            <img id="chosen-image">
                                                                        </figure>

                                                                        @csrf
                                                                        <input type="file" id="upload-button" name="photo" accept="image/*">
                                                                        <label for="upload-button" class="upload-label">
                                                                            <i class="fas fa-upload"></i> &nbsp; Upload Photo
                                                                        </label>
                                                                    </div>
                                                                    
                                                                    <div class="modal-footer">
                                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                                                        <button type="submit" class="btn btn-primary">Save Changes</button>
                                                                    </div>
                                                                </form>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="profile-details-settings-2 text-sm-right">
                                                        <span class="badge badge-secondary bg-primary">{{ $user->role }}</span>
                                                        <span class="badge badge-secondary bg-primary">{{ $user->campusEntity->campus }}</span>
                                                        <div class="text-muted">
                                                            <small>Joined {{ \Carbon\Carbon::parse($user->created_at)->format('F j, Y') }}</small>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="tab-content pt-3">
                                                <div class="tab-pane active">
                                                    <span class="m-0 text-1">{{ __('Manage Account') }}</span>
                                                    <div class="row m-2">
                                                        <form class="form" action="{{ route('profile.update', ['id' => $user->id]) }}" method="post">
                                                            @method('PUT')
                                                            @csrf
                                                            @if (session('success-account'))
                                                            <div class="alert alert-success alert-dismissible" role="alert">
                                                                <div>{{ session('success-account') }}</div>
                                                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                                            </div>
                                                            @endif
                                                            
                                                            <div class="col">
                                                                <div class="row">
                                                                    <div class="col">
                                                                        <div class="form-group">
                                                                            <label>{{ __('Email') }}</label>
                                                                            <input class="form-control" type="email" name="email" placeholder="{{  __('Enter email') }}" value="{{ $user->email }}">
                                                                        </div>
                                                                        @error('email')
                                                                        <span class="text-danger fw-bold small mt-1" role="alert">
                                                                            {{ $message }}
                                                                        </span>
                                                                        @enderror
                                                                        @if (session('error-email'))
                                                                        <span class="text-danger fw-bold small mt-1" role="alert">
                                                                            {{ session('error-email') }}
                                                                        </span>
                                                                        @endif
                                                                    </div>
                                                                    <div class="col">
                                                                        <div class="form-group">
                                                                            <label>{{ __('Username') }}</label>
                                                                            <input class="form-control" type="text" name="username" placeholder="{{ __('Enter username') }}" value="{{ $user->username }}">
                                                                        </div>
                                                                        @error('username')
                                                                        <span class="text-danger fw-bold small mt-1" role="alert">
                                                                            {{ $message }}
                                                                        </span>
                                                                        @enderror
                                                                    </div>
                                                                </div>

                                                                @if ( $user->role == "Organizer" )
                                                                <div class="row pt-3">
                                                                    <div class="col">
                                                                        <div class="form-group">
                                                                            <label>{{ __('Department') }}</label>
                                                                            <input class="form-control" type="text" name="department" value="@if($user->campusEntity->department){{ $user->campusEntity->department->department }}@endif" disabled>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                
                                                                <div class="row pt-3">
                                                                    <div class="col">
                                                                        <div class="form-group">
                                                                            <label>{{ __('Organization') }}</label>
                                                                            <input class="form-control" type="text" name="organization" value="@if($user->campusEntity->department){{ $user->campusEntity->department->department }}@endif" disabled>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                @endif

                                                                <div class="d-flex justify-content-end align-items-start mt-3">
                                                                    <button class="btn btn-primary" type="submit">
                                                                        {{ __('Save Changes') }}
                                                                    </button>
                                                                </div>
                                                                
                                                            </div>
                                                        </form>
                                                    </div>

                                                    
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="card mt-4">
                                    <div class="card-body">
                                        <div class="e-profile">
                                            <div class="tab-content">
                                                <div class="tab-pane active">
                                                    <span class="m-0 text-1">{{ __('Change Password') }}</span>
                                                    <div class="row m-2">
                                                        <div class="col-12 col-sm-12 mb-2">
                                                            <form class="form" action="{{ route('profile.password', ['id' => $user->id]) }}" method="post">
                                                                @method('PUT')
                                                                @csrf
                                                                @if (session('success-password'))
                                                                <div class="alert alert-success alert-dismissible" role="alert">
                                                                    <div>{{ session('success-password') }}</div>
                                                                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                                                </div>
                                                                @endif

                                                                <div class="form-group mb-2">
                                                                    <label>{{ __('Current Password') }}</label>
                                                                    <input class="form-control" id="password-current" name="current_password" type="password" placeholder="Enter current password">
                                                                </div>
                                                                @error ('current_password')
                                                                <span class="text-danger fw-bold small mt-1" role="alert">
                                                                    {{ $message }}
                                                                </span>
                                                                @enderror
                                                                @if (session('error-current'))
                                                                <span class="text-danger fw-bold small mt-1" role="alert">
                                                                    {{ session('error-current') }}
                                                                </span>
                                                                @endif

                                                                <div class="form-group mb-2">
                                                                    <label>{{ __('New Password') }}</label>
                                                                    <input class="form-control" id="password-new" name="password" type="password" placeholder="{{ __('Enter new password') }}">
                                                                </div>
                                                                <div id="password-strength"></div>
                                                                @error ('password')
                                                                <span class="text-danger fw-bold small mt-1" role="alert">
                                                                    {{ $message }}
                                                                </span>
                                                                @enderror
                                                                @if (session('error-password'))
                                                                <span class="text-danger fw-bold small mt-1" role="alert">
                                                                    {{ session('error-password') }}
                                                                </span>
                                                                @endif
                                                                
                                                                <div class="form-group mb-2">
                                                                    <label>{{ __('Confirm Password') }}</label>
                                                                    <input class="form-control" id="password-confirm" name="password_confirmation" type="password" placeholder="{{ __('Confirm password') }}">
                                                                </div>
                                                                @if (session('error-confirm'))
                                                                <span class="text-danger fw-bold small mt-1" role="alert">
                                                                    {{ session('error-confirm') }}
                                                                </span>
                                                                @endif
                                                                
                                                                <div class="d-flex justify-content-between align-items-start mt-2">
                                                                    <div class="form-check">
                                                                        <input class="form-check-input" id="show-passwords" type="checkbox">
                                                                        <span class="small" for="show-passwords">
                                                                            {{ __('Show password') }}
                                                                        </span>
                                                                    </div>
                                                                    <button class="btn btn-primary mt-1" type="submit">
                                                                        {{ __('Save Changes') }}
                                                                    </button>
                                                                </div>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 col-md-3 mb-3">
                                @if ( $user->role == "User" )
                                <div class="card mb-4">
                                    <div class="card-body">
                                        @if (session('success-request'))
                                        <div class="alert alert-success alert-dismissible" role="alert">
                                            <div>{{ session('success-request') }}</div>
                                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                        </div>
                                        @endif
                                        <span class="m-0 text-1">{{ __('Join Us') }}</span>
                                        <p class="card-text">Join organizations and help us create exciting events!</p>
                                        
                                        <div class="col-md-12 m-0 p-0 mb-2 d-flex justify-content-center">
                                            <button class="btn btn-primary mt-1" type="button" data-bs-toggle="modal" data-bs-target="#requestModal">
                                                {{ __('Become an Organizer') }}
                                            </button>
                                        </div>
                                    </div>
                                    <div class="card-footer">                                        
                                        <p class="small m-0 p-2 text-muted text-justify" style="text-align: justify !important;">Please Note: In order to become an organizer, you must first join organizations from this campus. Your request to become an organizer will be reviewed by the admin, and you will receive a notification regarding the status of your request.</p>
                                    </div>
                                </div>

                                <!-- modal -->
                                <div class="modal fade" id="requestModal" tabindex="-1" aria-labelledby="requestModalLabel" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h1 class="modal-title fs-5" id="requestModalLabel">Become an Organizer</h1>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <form action="{{ route('profile.request', ['id' => $user->id]) }}" method="post" id="request-form">
                                                @csrf
                                                <div class="modal-body">
                                                    <div class="mb-3">
                                                        <label class="form-label">Organization</label>
                                                        <select class="form-select mb-2" id="organization-select" name="organization">
                                                            <option value="" selected>Select organization</option>
                                                            @foreach($organizations as $organization)
                                                            <option value="{{ $organization->id }}">{{ $organization->organization }}</option>
                                                            @endforeach
                                                        </select>
                                                        @error ('organization')
                                                        <span class="text-danger fw-bold small mt-1" role="alert">
                                                            {{ $message }}
                                                        </span>
                                                        @enderror
                                                        <p class="text-danger fw-bold small mt-1" id="error-request"></p>
                                                        <small class="form-text text-muted">Please choose the organization you are a member of. If you belong to multiple organizations, select one.</small>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                                    <button type="submit" class="btn btn-primary">Submit Request</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                                @endif
                                <div class="card">
                                    <div class="card-body">
                                        <span class="m-0 text-1">{{ __('Support') }}</span>
                                        <p class="card-text">Get fast, free help from Elevento Team.</p>
                                        <p class="card-text mb-1 text-center">Connect with us:
                                        <div class="col-md-12 m-0 p-0 d-flex justify-content-center">
                                            <a href="#" class="text-dark mx-2"><i class="fab fa-facebook-f" style="font-size: 18px; color: #3b5998;"></i></a>
                                            <a href="#" class="text-dark mx-2"><i class="fab fa-twitter" style="font-size: 18px; color: #00aced;"></i></a>
                                            <a href="#" class="text-dark mx-2"><i class="fab fa-instagram" style="font-size: 18px; color: #231f20;"></i></a>
                                            <a href="#" class="text-dark mx-2"><i class="fab fa-linkedin" style="font-size: 18px; color: #3B5998;"></i></a>
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

        // Upload Photo
        $("#upload-button").change(function() {
            let reader = new FileReader();
            reader.readAsDataURL(this.files[0]);
            reader.onload = function() {
                $("#chosen-image").attr("src", reader.result);
                $(".image-container").show();
            };
        });

        // Show password register form
        const $passwordCurrentInput = $('#password-current');
        const $passwordNewInput = $('#password-new');
        const $passwordConfirmInput = $('#password-confirm');

        $('#show-passwords').click(function() {
            if ($(this).is(':checked')) {
                $passwordCurrentInput.attr('type', 'text');
                $passwordNewInput.attr('type', 'text');
                $passwordConfirmInput.attr('type', 'text');
            } else {
                $passwordCurrentInput.attr('type', 'password');
                $passwordNewInput.attr('type', 'password');
                $passwordConfirmInput.attr('type', 'password');
            }
        });

        // Password strength
        const $passwordStrength = $('#password-strength');

        $passwordNewInput.on('input', function () {
            const password = $passwordNewInput.val().trim();
            
            let strengthMessage = '';
            let passwordStrengthFlag = 0;

            if (password.length > 8) {
            passwordStrengthFlag++;
            }

            if (password.match(/[a-z]/)) {
            passwordStrengthFlag++;
            }

            if (password.match(/[A-Z]/)) {
            passwordStrengthFlag++;
            }

            if (password.match(/\d/)) {
            passwordStrengthFlag++;
            }

            if (password.match(/[!@#$%^&*()_+{}\[\]:;<>,.?~\\|\-=]/)) {
            passwordStrengthFlag++;
            }

            switch (passwordStrengthFlag) {
            case 1:
                strengthMessage = 'Weak password';
                $passwordStrength.removeClass().addClass('text-danger fw-bold small mt-1');
                break;
            case 2:
                strengthMessage = 'Moderate password';
                $passwordStrength.removeClass().addClass('text-warning fw-bold small mt-1');
                break;
            case 3:
            case 4:
                strengthMessage = 'Strong password';
                $passwordStrength.removeClass().addClass('text-success fw-bold small mt-1');
                break;
            case 5:
                strengthMessage = 'Very strong password';
                break;
            default:
                strengthMessage = '';
            }

            $passwordStrength.text(strengthMessage);
        });

        // Request form
        $('#request-form').on('submit', function(e){
            e.preventDefault();

            var organization = $('#organization-select').val();

            if(organization === '') {
                $('#error-request').text('Please select an organization.');
                return false; 
            }

            this.submit();
        });
    });
</script>
@endsection
