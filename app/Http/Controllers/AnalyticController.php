<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Registration;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AnalyticController extends Controller
{
    public function index($eventId)
    {
        $user = Auth::user();

        // $attendance = Registration::with('user')
        // ->with('user.campusEntity')
        // ->where('event_id', $eventId)
        // ->where('status', 'attended')
        // ->get();

        $event = Event::where('id', $eventId)
        ->first();

        $totalHearts = $event->hearts;
        $totalRegistration = $event->register;
        
        $attendance = Registration::where('event_id', $eventId)
        ->where('status', 'attended')
        ->get();
    
        // Count the total number of attendees
        $totalAttendees = $attendance->count();

        // // Count the total number of registration
        // $totalRegistration = Registration::with('user')
        // ->where('event_id', $eventId)
        // ->count();
    
        // // Count the total number of attendees
        // $totalAttendees = $attendance->count();
        
        // // Count the number of Male and Female attendees
        // $maleAttendees = $attendance->where('user.campusEntity.sex', 'Male')->count();
        // $femaleAttendees = $attendance->where('user.campusEntity.sex', 'Female')->count();
        
        return view('planalytics', ['user' => $user, 'eventId' => $eventId, 'totalHearts' => $totalHearts, 'totalRegistration' => $totalRegistration, 'totalAttendees' => $totalAttendees]);
    }
}
