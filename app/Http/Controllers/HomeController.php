<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

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
    public function attendeeHome()
    {
        return view('user_attendee.home');
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
        return view('user_admin.home');
    }
}