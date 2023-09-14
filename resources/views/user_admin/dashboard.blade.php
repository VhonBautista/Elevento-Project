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
                <a href="{{ route('admin.management') }}">
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
                                <div class="col-md-8 mb-3">

                                    <span class="m-0 text-1">{{ __('Happening Today') }}</span>
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
                            
                                </div>

                                <div class="col-md-4">

                                    <div class="alert alert-danger d-none" role="alert" id="weather-error">
                                        <div id="weather-message"></div>
                                    </div>
                                    <span class="m-0 text-1">{{ __('Weather Today') }}</span>
                                    <div class="weather-data mb-2 mt-2">
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
                                        <div class="weather-input mb-3">
                                            <input class="city-input" type="text" placeholder="Search for a city" value="{{ session('campus') }}">
                                            <button class="search-btn">Search</button>
                                        </div>
                                        <div class="days-forecast">
                                            <span class="m-0 text-1">{{ __('5 Day Forecast') }}</span>
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
                                   
                                    <span class="m-0 text-1 liner">{{ __('Pending Events') }}</span>
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
                                                                <a href="" class="btn btn-primary py-0">Manage</a>
                                                            </div>
                                                        </div>
                                                    </td>
                                                </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
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
        var calendarEl = document.getElementById('calendar');
        var calendar = new FullCalendar.Calendar(calendarEl, {
            headerToolbar: {
                left: 'prev,next today',
                center: 'title',
                right: 'dayGridMonth,timeGridWeek,timeGridDay,listMonth',
            },
            selectable: true,
            
            events: [
                    {
                    title  : 'event1',
                    start  : '2023-09-05'
                    },
                    {
                    title  : 'event2',
                    start  : '2023-09-05',
                    end    : '2023-09-11'
                    },
                    {
                    title  : 'event3',
                    start  : '2023-09-05 13:38:09',
                    }
                ],
            eventColor: '#378006'

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
