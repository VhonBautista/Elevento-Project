<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Favorite;
use App\Models\Registration;
use App\Models\User;
use App\Notifications\AllNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Notification;

class PreviewController extends Controller
{
    public function index($eventId)
    {
        $user = Auth::user();
        $event = Event::with('venue')->with('creator')->with('peoples')->with('images')->with('segments.flows')->findOrFail($eventId);
        $segments = $event->segments;
        $peoples = $event->peoples;
        $images = $event->images;

        
        $registration = Registration::with('user')
            ->where('user_id', $user->id)
            ->where('event_id', $eventId)
            ->first();

        $relatedEvents = Event::with('creator')
            ->where('event_type', $event->event_type)
            ->where('status', 'Active')
            ->where('id', '!=', $eventId)
            ->orderBy('start_date', 'asc')
            ->take(7)
            ->get();

        if ($relatedEvents->isEmpty()) {
            $relatedEvents = Event::with('creator')
                ->where('status', 'Active')
                ->where('id', '!=', $eventId)
                ->orderBy('start_date', 'asc')
                ->take(7)
                ->get();
        }

        // check if auth user has registration record existing for this event
        $hasProject = Auth::user()->projects()->where('event_id', $eventId)->exists();
        $projectCount = Auth::user()->projects()->where('event_id', $eventId)->count();
        $existingFavorite = Favorite::where('user_id', $user->id)->where('event_id', $eventId)->exists();


        $hasMultipleUsers = $projectCount > 1;

        return view('preview', ['event' => $event, 'images' => $images, 'relatedEvents' => $relatedEvents, 'registration' => $registration, 'segments' => $segments, 'peoples' => $peoples, 'hasProject' => $hasProject, 'existingFavorite' => $existingFavorite, 'hasMultipleUsers' => $hasMultipleUsers, 'user' => $user]);
    }

    public function updateHearts(Request $request)
    {
        $eventId = $request->input('eventId');
        $user = Auth::user();
        $userId = Auth::id();

        $existingFavorite = Favorite::where('user_id', $userId)->where('event_id', $eventId)->first();
        $event = Event::with('creator')->find($eventId);
        $creator = User::findOrFail($event->creator_id);

        if ($existingFavorite) {
            $existingFavorite->delete();
            $event->decrement('hearts');

            // Notification
            if ($user->id !== $event->creator_id) {
                $title = 'Removed from Favorites';
                $type = 'Updated';
                $message = $user->username . ' removed ' . $event->title . ' from their favorites.';
                $url = '/events/plan/' . $event->id;

                Notification::send($creator, new AllNotification($title, $type, $message, $url));
            }
        } else {
            $event->increment('hearts');
            Favorite::create([
                'user_id' => $userId,
                'event_id' => $eventId,
            ]);

            // Notification
            if ($user->id !== $event->creator_id) {
                $title = 'Added to Favorites';
                $type = 'Updated';
                $message = $user->username . ' added ' . $event->title . ' to their favorites.';
                $url = '/events/plan/' . $event->id;

                Notification::send($creator, new AllNotification($title, $type, $message, $url));
            }
        }

        if(true) {
            return redirect()->route('preview', ['eventId' => $eventId])->with('hidden-success', 'Added to favorites');
        } else {
            return redirect()->route('preview', ['eventId' => $eventId])->with('error', 'Something went wrong.');
        }
    }

    public function registerEvent(Request $request)
    {
        $user = Auth::user();
        $userId = Auth::id();
        $event = Event::findOrFail($request->input('eventId'));

        // Check if there's an existing registration for the same user and event
        $existingRegistration = Registration::where('user_id', $userId)
            ->where('event_id', $event->id)
            ->first();

        if ($existingRegistration) {
            return response()->json([
                'error' => 'You are already registered for this event.',
                'code' => 400,
            ]);
        }

        $event->title = str_replace(' ', '', $event->title);
        $event->increment('register');
        $qrCode = $user->username . '_' . $event->title . '_' . uniqid();

        $registration = Registration::create([
            'user_id' => $userId,
            'event_id' => $event->id,
            'qr_code' => $qrCode,
        ]);
        
        $creator = User::findOrFail($event->creator_id);

        // Notification
        if ($user->id !== $event->creator_id) {
            $title = 'Registered';
            $type = 'Updated';
            $message = $user->username . ' just registered to your event ' . $event->title . '.';
            $url = '/events/plan/' . $event->id;

            Notification::send($creator, new AllNotification($title, $type, $message, $url));
        }

        if($registration) {
            return response()->json([
                'success' => 'Make sure to download your QR code attendance pass.',
                'data' => $registration->qr_code,
                'code' => 200,
            ]);
        } else {
            return response()->json([
                'error' => 'Internal server error.',
                'code' => 500
            ]);
        }
    }
}
