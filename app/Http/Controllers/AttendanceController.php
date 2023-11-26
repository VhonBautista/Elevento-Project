<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Registration;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AttendanceController extends Controller
{
    public function index($eventId)
    {
        $user = Auth::user();

        $attendance = Registration::with('user')
        ->with('user.campusEntity')
        ->where('event_id', $eventId)
        ->where('status', 'attended')
        ->get();

        $event = Event::where('id', $eventId)
        ->first();

        // Count the total number of registration
        $totalRegistration = Registration::with('user')
        ->where('event_id', $eventId)
        ->count();
    
        // Count the total number of attendees
        $totalAttendees = $attendance->count();
        
        // Count the number of Male and Female attendees
        $maleAttendees = $attendance->where('user.campusEntity.sex', 'Male')->count();
        $femaleAttendees = $attendance->where('user.campusEntity.sex', 'Female')->count();
        
        return view('planattendance', ['user' => $user, 'eventId' => $eventId, 'event' => $event, 'totalAttendees' => $totalAttendees, 'maleAttendees' => $maleAttendees, 'femaleAttendees' => $femaleAttendees, 'totalRegistration' => $totalRegistration]);
    }

    public function getAttendanceData($eventId)
    {
        $attendance = Registration::with('user')->with(['user.campusEntity'])
            ->where('event_id', $eventId)
            ->where('status', 'attended')
            ->orderByDesc('time_in')
            ->get();
    
        $formattedData = $attendance->map(function ($item) {
            return [
                'id' => $item->id,
                'user_id' => $item->user->user_id,
                'lastname' => $item->user->campusEntity->lastname,
                'firstname' => $item->user->campusEntity->firstname,
                'middlename' => $item->user->campusEntity->middlename,
                'status' => $item->status,
                'time_in' => $item->time_in,
            ];
        });

        return response()->json([
            'data' => $formattedData,
        ]);
    }

    public function attendanceCheck(Request $request)
    {
        $registration = Registration::where('qr_code', $request->qrPass)->first();
        $existingAttencance = $registration->where('status', 'attended')->exists();

        if($existingAttencance){
            return response()->json([
                'error' => 'QR Pass has already been scanned.',
                'code' => 200
            ]);
        }
        
        // Get the current timestamp
        date_default_timezone_set('Asia/Manila');
        $currentTimestamp = date('Y-m-d H:i:s');

        // Update the record
        $affectedRows = $registration->update([
            'status' => 'attended',
            'time_in' => $currentTimestamp
        ]);
        
        $attendance = Registration::with('user')
        ->with('user.campusEntity')
        ->where('event_id', $registration->event_id)
        ->where('status', 'attended')
        ->get();
    
        // Count the total number of attendees
        $totalAttendees = $attendance->count();
        
        // Count the number of Male and Female attendees
        $maleAttendees = $attendance->where('user.campusEntity.sex', 'Male')->count();
        $femaleAttendees = $attendance->where('user.campusEntity.sex', 'Female')->count();
        
        if($affectedRows) {
            return response()->json([
                'success' => true,
                'totalAttendees' => $totalAttendees,
                'maleAttendees' => $maleAttendees,
                'femaleAttendees' => $femaleAttendees,
                'code' => 200
            ]);
        } else {
            return response()->json([
                'error' => true,
                'code' => 200
            ]);
        }
    }
}
