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
                <a href="{{ route('admin.dashboard') }}" class="active">
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
                            <img src="{{ asset(Auth::user()->profile_picture) }}" alt="Avatar">
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
                    <button class="nav-link active" id="dashboard-tab" data-bs-toggle="tab" data-bs-target="#dashboard" type="button" role="tab" aria-controls="dashboard" aria-selected="true">Dashboard</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="analytics-tab" data-bs-toggle="tab" data-bs-target="#analytics" type="button" role="tab" aria-controls="analytics" aria-selected="false">Analytics</button>
                </li>
            </ul>

            <div class="tab-content" id="myTabContent">
                <div class="tab-pane fade p-2 show active" id="dashboard" role="tabpanel" aria-labelledby="dashboard-tab">
                    <div class="row flex-lg-nowrap mt-2">
                        <div class="col">
                            <div class="row">
                                <div class="col mb-3">

                                    <div class="card">
                                        <div class="card-body">
                                            col 1
                                        </div>
                                    </div>

                                    <div class="card mt-4">
                                        <div class="card-body">
                                            col 2
                                        </div>
                                    </div>
                                </div>

                                <div class="col-12 col-md-5 mb-3">
                                    <div class="card">
                                        <div class="card-body p-4">

                                                <div class="alert alert-danger d-none" role="alert" id="weather-error">
                                                    <div id="weather-message"></div>
                                                </div>
                                            <span class="m-0 text-1">{{ __('Weather Today') }}</span>
                                            <div class="weather-data mt-2">
                                                <div class="current-weather mb-4">
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
                                                        <div class="spinner-border" role="status">
                                                            <span class="visually-hidden">Loading...</span>
                                                        </div>
                                                        <p class="card-text placeholder-glow">
                                                            <span class="placeholder col-7"></span>
                                                        </p> 
                                                    </div>
                                                </div>
                                                <div class="days-forecast">
                                                    <span class="m-0 text-1">{{ __('5 Day Forecast') }}</span>
                                                    <div class="weather-cards p-0 mt-2">
                                                        <div class="items" style="width: 100%;">
                                                            <h5 class="card-title placeholder-glow">
                                                                <span class="placeholder col-6"></span>
                                                            </h5>
                                                            <div class="spinner-border" role="status">
                                                                <span class="visually-hidden">Loading...</span>
                                                            </div>
                                                            <p class="card-text placeholder-glow">
                                                                <span class="placeholder col-7"></span>
                                                                <span class="placeholder col-4"></span>
                                                                <span class="placeholder col-4"></span>
                                                                <span class="placeholder col-6"></span>
                                                                <span class="placeholder col-8"></span>
                                                            </p>  
                                                        </div>
                                                        <div class="items" style="width: 100%;">
                                                            <h5 class="card-title placeholder-glow">
                                                                <span class="placeholder col-6"></span>
                                                            </h5>
                                                            <div class="spinner-border" role="status">
                                                                <span class="visually-hidden">Loading...</span>
                                                            </div>
                                                            <p class="card-text placeholder-glow">
                                                                <span class="placeholder col-7"></span>
                                                                <span class="placeholder col-4"></span>
                                                                <span class="placeholder col-4"></span>
                                                                <span class="placeholder col-6"></span>
                                                                <span class="placeholder col-8"></span>
                                                            </p>  
                                                        </div>
                                                        <div class="items" style="width: 100%;">
                                                            <h5 class="card-title placeholder-glow">
                                                                <span class="placeholder col-6"></span>
                                                            </h5>
                                                            <div class="spinner-border" role="status">
                                                                <span class="visually-hidden">Loading...</span>
                                                            </div>
                                                            <p class="card-text placeholder-glow">
                                                                <span class="placeholder col-7"></span>
                                                                <span class="placeholder col-4"></span>
                                                                <span class="placeholder col-4"></span>
                                                                <span class="placeholder col-6"></span>
                                                                <span class="placeholder col-8"></span>
                                                            </p>  
                                                        </div>
                                                        <div class="items" style="width: 100%;">
                                                            <h5 class="card-title placeholder-glow">
                                                                <span class="placeholder col-6"></span>
                                                            </h5>
                                                            <div class="spinner-border" role="status">
                                                                <span class="visually-hidden">Loading...</span>
                                                            </div>
                                                            <p class="card-text placeholder-glow">
                                                                <span class="placeholder col-7"></span>
                                                                <span class="placeholder col-4"></span>
                                                                <span class="placeholder col-4"></span>
                                                                <span class="placeholder col-6"></span>
                                                                <span class="placeholder col-8"></span>
                                                            </p>  
                                                        </div>
                                                        <div class="items" style="width: 100%;">
                                                            <h5 class="card-title placeholder-glow">
                                                                <span class="placeholder col-6"></span>
                                                            </h5>
                                                            <div class="spinner-border" role="status">
                                                                <span class="visually-hidden">Loading...</span>
                                                            </div>
                                                            <p class="card-text placeholder-glow">
                                                                <span class="placeholder col-7"></span>
                                                                <span class="placeholder col-4"></span>
                                                                <span class="placeholder col-4"></span>
                                                                <span class="placeholder col-6"></span>
                                                                <span class="placeholder col-8"></span>
                                                            </p>  
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="weather-input mb-4">
                                                <input class="city-input" type="text" placeholder="Search for a city" value="{{ session('campus') }}">
                                                <button class="search-btn">Search</button>
                                            </div>

                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>

                <div class="tab-pane fade p-2" id="analytics" role="tabpanel" aria-labelledby="analytics-tab">
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
                            <div class="card bg-warning text-white mb-4">
                                <div class="card-body">Warning Card</div>
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
                    <div class="row mt-2">
                        <div class="col-xl-6">
                            <div class="card mb-4">
                                <div class="card-header">
                                    <i class="fas fa-chart-area me-1"></i>
                                    Area Chart Example
                                </div>
                                <div class="card-body">
                                    <canvas id="myAreaChart" width="100%" height="40"></canvas>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-6">
                            <div class="card mb-4">
                                <div class="card-header">
                                    <i class="fas fa-chart-bar me-1"></i>
                                    Bar Chart Example
                                </div>
                                <div class="card-body"><canvas id="myBarChart" width="100%" height="40"></canvas></div>
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
                    <h2>${cityName}, ${formattedDate}</h2>
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
                <div class="items">
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
    });
</script>
@endsection
