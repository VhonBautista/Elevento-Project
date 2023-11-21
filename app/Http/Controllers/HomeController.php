<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\EventType;
use App\Models\Event;
use App\Models\Campus;
use App\Models\Venue;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the attendee dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function home()
    {
        return view('home');
    }

    /**
     * Show the organizer dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function organizerHome()
    {
        return view('user_organizer.home');
    }

    /**
     * Show the admin dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function adminHome()
    {
        $campus = session('campus');
        $events = Event::where('status', 'Pending')
               ->where('campus', $campus)
               ->orderBy('created_at', 'desc')
               ->take(5)
               ->get();
            
        $calendarEvents = array();
        $bookings = Event::join('venues', 'events.venue_id', '=', 'venues.id')
        ->select('events.*', 'venues.venue_name')
        ->where('events.campus', $campus)
        ->whereIn('events.status', ['planning', 'active'])
        ->get();

        foreach($bookings as $booking){
            $calendarEvents[] = [
                'id' => $booking->id,
                'title' => $booking->title,
                'start' => $booking->start_date,
                'end' => $booking->end_date,
                'desc' => $booking->description,
                'type' => $booking->event_type,
                'audience' => $booking->target_audience,
                'venue' => $booking->venue_name
            ];
        }

        $eventTypes = EventType::all();

        $campuses = Campus::all();

        return view('user_admin.dashboard', ['events' => $events, 'calendar' => $calendarEvents, 'eventTypes' => $eventTypes, 'campuses' => $campuses]);
    }

    public function getVenues($campus) {
        $venues = Venue::select(
            'id',
            'venue_name',
            'handler_name',
            'capacity',
            'image'
            )
        ->where('campus', $campus)
        ->where('status', 'Active')
        ->orderBy('venue_name')
        ->get();
        
        return response()->json($venues);
    }
}