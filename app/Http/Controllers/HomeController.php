<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Event;

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

        return view('user_admin.dashboard', ['events' => $events]);
    }
}