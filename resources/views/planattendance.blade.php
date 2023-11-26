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
.dataTables_wrapper .dataTables_filter, .dataTables_wrapper .dataTables_filter label {
    width: 350px !important;
    margin-top: -42px !important;
}
.dataTables_wrapper .dataTables_filter input {
    border: 2px solid #1c1d1d !important;
    border-radius: 12px !important;
    padding: 2px 16px !important;
    margin-left: 8px !important;
    margin-bottom: 12px !important;
    width: 75% !important;
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
                            <a class="nav-link text-light" aria-current="page" href="{{ route('plan', ['eventId' => $eventId]) }}">Plan</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-light" href="{{ route('analytics', ['eventId' => $eventId]) }}">Analytics</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-light active fw-bold" href="{{ route('attendance', ['eventId' => $eventId]) }}">Attendance</a>
                        </li>
                    </ul>
                </li>
                <li class="nav-item d-flex align-items-center justify-content-center" style="margin-right: 20%;">
                    <span class="text-white">Music Event</span>
                </li>
                <li class="nav-item d-flex justify-content-end align-items-center">
                </li>
            </ul>
        </div>
    </div>
</nav>

<div class="container mt-3">
    <div class="row">
        <div class="col-lg-7 mb-3">
            <div class="d-flex justify-content-between align-items-center w-100">
                <span class="text my-0" style="font-size: 24px;"><i class="fa-solid fa-rectangle-list me-1"></i> Attendance List</span>
                <span class="text m-0 text-dark" style="font-size: 14px;"><i class="fa-regular fa-circle-user m-0 p-0"></i> Total Registration: <span class="m-0 fw-normal">{{ $totalRegistration }}</span></span>
            </div>
            <div class="row mt-2">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header bg-primary p-1">
                        </div>
                        <div class="card-body">
                            <div class="d-flex justify-content-center align-items-center p-5" id="attendance-loader">
                                <div class="spinner-border text-primary" role="status">
                                    <span class="visually-hidden">Loading...</span>
                                </div>
                            </div>
                            <table id="attendance-table" class="d-none">
                                <thead style="overflow-x: scroll !important;">
                                    <tr>
                                        <th>User ID</th>
                                        <th>Name</th>
                                        <th>Status</th>
                                        <th>Time In</th>
                                        {{-- <th></th> --}}
                                    </tr>
                                </thead>
                            </table>
                        </div>
                        <div class="card-footer bg-primary p-1">
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <style>
            #html5-qrcode-button-camera-start{
                display: none;
            }
        </style>
        <div class="col-lg-5">
            <span class="text" style="font-size: 24px;"><i class="fa-solid fa-qrcode me-1"></i> QR Scanner</span>
            <div class="row g-3 mt-2 mb-3">
                <div class="col-md-6 m-0">
                    <div class="card">
                        <div class="card-header bg-success p-1">
                        </div>
                        <div class="card-body pb-0">
                            <div class="d-flex justify-content-between text-secondary w-100 small align-items-center">
                                <strong>
                                    Total Participants
                                </strong>
                                <i class="fa-solid fa-people-group text-success"></i>
                            </div>
                            <h4 class="fw-bold mb-2 p-0" id="attendees-count">{{ $totalAttendees }}</h4>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 m-0">
                    <div class="card">
                        <div class="card-header bg-primary p-1">
                        </div>
                        <div class="card-body pb-0">
                            <div class="d-flex justify-content-between text-secondary w-100 small align-items-center">
                                <strong>
                                    Male
                                </strong>
                                <i class="fa-solid fa-mars text-primary"></i>
                            </div>
                            <h4 class="fw-bold mb-2 p-0" id="male-count">{{ $maleAttendees }}</h4>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 m-0">
                    <div class="card">
                        <div class="card-header bg-danger p-1">
                        </div>
                        <div class="card-body pb-0">
                            <div class="d-flex justify-content-between text-secondary w-100 small align-items-center">
                                <strong>
                                    Female
                                </strong>
                                <i class="fa-solid fa-venus text-danger"></i>
                            </div>
                            <h4 class="fw-bold mb-2 p-0" id="female-count">{{ $femaleAttendees }}</h4>
                        </div>
                    </div>
                </div>
            </div>

            <div id="reader" class="w-100 rounded overflow-hidden mb-1"></div>
            <div class="mx-4">
                <button class="btn my-1 btn-primary p-2 w-100" id="startScanButton">Start Scanning</button>
                <label for="html5-qrcode-button-camera-stop" class="btn my-2 btn-primary p-2 w-100 d-none" id="stopScanButton">Stop Scanning</label>
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
@endsection

@section('script')
<script src="https://unpkg.com/html5-qrcode" type="text/javascript">script</script>
<script>
    $(document).ready(function() {
        // DATA TABLE
        const attendanceLoader = $('#attendance-loader');
        const attendanceTable = $('#attendance-table');

        // attendance table
        attendanceLoader.addClass('d-none')
        attendanceTable.removeClass('d-none')

        //  call attendanceTableLoad.ajax.reload(); to reload the table
        var attendanceTableLoad = attendanceTable.DataTable({
            "scrollCollapse": true,
            "searching": true,
            "paging": true,
            "info": true,
            "lengthChange": false,
            "ordering": false,
            "scrollY": "600px",
            "pageLength": 10,

            dom: 'Bfrtip',
            buttons: [
                {
                    extend: 'excelHtml5',
                    text: 'Export to Excel',
                    filename: function () {
                        var currentDatetime = new Date();
                        var year = currentDatetime.getFullYear();
                        var month = String(currentDatetime.getMonth() + 1).padStart(2, '0');
                        var day = String(currentDatetime.getDate()).padStart(2, '0');
                        var formattedDatetime = year + '_' + month + '_' + day;

                        var eventTitle = @json($event->title);

                        return eventTitle + '_Attendance_Sheet_' + formattedDatetime;
                    },
                    className: 'btn btn-dark btn-sm custom-export-button'
                },
                {
                    extend: 'print',
                    text: 'Print',
                    className: 'btn btn-dark btn-sm align-self-end custom-export-button'
                }
            ],

            ajax: "{{ url('/events/get-attendance/' . $eventId) }}",
            columns: [
                {"data" : "user_id"},
                {
                    "data": null,
                    "render": function (data, type, row) {
                        return data.lastname + ', ' + data.firstname + ' ' + data.middlename;
                    }
                },
                {"data" : "status"},
                {
                    "data": "time_in",
                    "render": function (data, type, row) {
                        const date = new Date(data);
                        const formattedTime = date.toLocaleTimeString('en-US', {
                            hour: 'numeric',
                            minute: 'numeric',
                            hour12: true
                        });
                        return formattedTime;
                    }
                },
                // {
                //     "data": null,
                //     "render": function(data, type, full, meta) {
                //         return `
                //             <button type="button" data-id="${data.id}" class="delete-attendee-btn btn rounded btn-danger"><i class="fa-solid fa-x"></i></button>
                //         `;
                //     }
                // }
            ]
        });

        $('.dt-buttons').addClass('mb-3 w-50');

        // QR CODE SCANNER
        Html5Qrcode.getCameras().then(devices => {
            if (devices && devices.length) {
                var cameraId = devices[0].id;
                
                const html5QrCode = new Html5Qrcode("reader");
                const startScanButton = $('#startScanButton');
                const stopScanButton = $('#stopScanButton');

                startScanButton.on('click', function() {
                    $('#reader').removeClass('d-none');
                    startScanButton.addClass('d-none');
                    stopScanButton.removeClass('d-none');

                    html5QrCode.start(
                        cameraId, 
                        {
                            fps: 30,
                            qrbox: { width: 200, height: 200 }
                        },
                        (decodedText, decodedResult) => {
                            $.ajax({
                                url: "{{ route('attended') }}",
                                type: 'POST',
                                data: {
                                    "_token" : "{{ csrf_token() }}",
                                    "qrPass" : decodedText
                                },
                                dataType: 'json',
                                success: function(response) {
                                    if(response.success){
                                        $('#attendees-count').text(response.totalAttendees);
                                        $('#male-count').text(response.maleAttendees);
                                        $('#female-count').text(response.femaleAttendees);
                                        console.log(response);
                                        console.log(response.totalAttendees);
                                        console.log(response.maleAttendees);
                                        console.log(response.femaleAttendees);
                                        attendanceTableLoad.ajax.reload();
                                        Swal.fire({
                                            title: 'Scan Completed',
                                            text: 'QR Pass: ' + decodedText,
                                            icon: 'success',
                                            confirmButtonColor: '#3085d6',
                                            confirmButtonText: 'OK'
                                        })
                                    } else {
                                        Swal.fire({
                                            title: 'Error',
                                            text: response.error,
                                            icon: 'error',
                                            confirmButtonColor: '#3085d6',
                                            confirmButtonText: 'OK'
                                        })
                                    }
                                },
                                error: function(xhr, status, error) {
                                    console.error(xhr.responseText);
                                }
                            });
                        },
                        (errorMessage) => {
                            console.log(errorMessage);
                    })
                    .catch((err) => {
                    });
                });

                stopScanButton.on('click', function() {
                    html5QrCode.stop().then(() => {
                        $('#reader').addClass('d-none');
                        startScanButton.removeClass('d-none');
                        stopScanButton.addClass('d-none');
                    }).catch((err) => {
                        console.error(err);
                    });
                });     
            }
        }).catch(err => {
        });
    });
</script>
@endsection
