<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\EventType;
use App\Models\Event;
use App\Models\Campus;
use App\Models\Venue;
use Illuminate\Support\Facades\Auth;

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
        // ALL ACTIVE PROJECTS
        $user = Auth::user();
        $campus = session('campus');
        $query = Event::with('creator');
    
        // Filter by event type
        if ($eventType = request('eventType')) {
            $query->where('event_type', $eventType);
        }
    
        // Filter by target audience
        if ($targetAudience = request('targetAudience')) {
            $query->where('target_audience', $targetAudience);
        }
        
        // Search by event title or creator's username
        if ($search = request('search')) {
            $query->where(function ($q) use ($search, $campus) {
                $q->where('title', 'like', '%' . $search . '%')->where('campus', $campus)
                    ->orWhereHas('creator', function ($subq) use ($search) {
                        $subq->where('username', 'like', '%' . $search . '%');
                    });
            });
        }

        // Filter by event status
        $query->where('status', 'active')->where('campus', $campus);

        $activeProjects = $query->get();


        // UPCOMING EVENT
        $today = now();

        $upcomingEvents = Event::with('creator')
            ->where('status', 'active')
            ->where('campus', $campus)
            ->where('start_date', '>=', $today)
            ->where('start_date', '<=', $today->clone()->addDays(3))
            ->get();

        // EVENT TYPES
        $eventTypes = EventType::all();

        return view('home', ['activeProjects' => $activeProjects, 'upcomingEvents' => $upcomingEvents, 'eventTypes' => $eventTypes, 'user' => $user, 'eventType' => $eventType, 'targetAudience' => $targetAudience, 'search' => $search]);
    }

    /**
     * Show the organizer dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function organizerHome()
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

        return view('user_organizer.home', ['events' => $events, 'calendar' => $calendarEvents, 'eventTypes' => $eventTypes, 'campuses' => $campuses]);
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