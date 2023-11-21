<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Event;
use App\Models\Segment;
use Carbon\Carbon;

class ApprovalController extends Controller
{
    public function index()
    {
        $campus = session('campus');

        $pendingEventsCount = Event::where('status', 'Pending')->where('campus', $campus)->count();
        $planningEventsCount = Event::where('status', 'Planning')->where('campus', $campus)->count();
        $activeEventsCount = Event::where('status', 'Active')->where('campus', $campus)->count();
        $rejectedEventsCount = Event::where('status', 'Inactive')->where('campus', $campus)->count();

        return view('user_admin.approval')
        ->with('pendingEventsCount', $pendingEventsCount)
        ->with('planningEventsCount', $planningEventsCount)
        ->with('activeEventsCount', $activeEventsCount)
        ->with('rejectedEventsCount', $rejectedEventsCount);
    }

    public function getUpdatedEventCounts()
    {
        $campus = session('campus');

        $pendingEventsCount = Event::where('status', 'Pending')->where('campus', $campus)->count();
        $planningEventsCount = Event::where('status', 'Planning')->where('campus', $campus)->count();
        $activeEventsCount = Event::where('status', 'Active')->where('campus', $campus)->count();
        $rejectedEventsCount = Event::where('status', 'Inactive')->where('campus', $campus)->count();

        return response()->json([
            'activeEventsCount' => $activeEventsCount,
            'pendingEventsCount' => $pendingEventsCount,
            'rejectedEventsCount' => $rejectedEventsCount,
            'planningEventsCount' => $planningEventsCount,
        ]);
    }

    public function getApprovals()
    {
        $campus = session('campus');
        $events = Event::leftJoin('users', 'users.id', '=', 'events.creator_id')
        ->leftJoin('venues', 'venues.id', '=', 'events.venue_id')
        ->where('events.campus', '=', $campus)
        ->where('events.status', '=', 'Pending')
        ->select('events.*', 'users.username', 'venues.venue_name')
        ->orderBy('start_date', 'desc')
        ->get();
        
        if ($events) {
            return response()->json([
                'message' => 'Data is present.',
                'code' => 200,
                'data' => $events
            ]);
        } else {
            return response()->json([
                'message' => 'Internal server error.',
                'code' => 500
            ]);
        }
    }

    public function getRejected()
    {
        $campus = session('campus');
        $events = Event::leftJoin('users', 'users.id', '=', 'events.creator_id')
        ->leftJoin('venues', 'venues.id', '=', 'events.venue_id')
        ->where('events.campus', '=', $campus)
        ->where('events.status', '=', 'Inactive')
        ->select('events.*', 'users.username', 'venues.venue_name')
        ->orderBy('updated_at')
        ->get();
        
        if ($events) {
            return response()->json([
                'message' => 'Data is present.',
                'code' => 200,
                'data' => $events
            ]);
        } else {
            return response()->json([
                'message' => 'Internal server error.',
                'code' => 500
            ]);
        }
    }

    public function acceptEvent(Request $request)
    {
        $campus = session('campus');
        $affectedRows = Event::where('id', $request->id)->update([
            'status' => 'Planning'
        ]);
        
        if ($affectedRows > 0) {
            $event = Event::find($request->id);
        
            $startDate = Carbon::parse($event->start_date);
            $endDate = Carbon::parse($event->end_date)->subDay();
        
            while ($startDate <= $endDate) {
                Segment::create([
                    'event_id' => $event->id,
                    'date' => $startDate,
                ]);
        
                $startDate->addDay();
            }
        }
        
        $pendingEventsCount = Event::where('status', 'Pending')->where('campus', $campus)->count();
        $planningEventsCount = Event::where('status', 'Planning')->where('campus', $campus)->count();
        $activeEventsCount = Event::where('status', 'Active')->where('campus', $campus)->count();
        $rejectedEventsCount = Event::where('status', 'Inactive')->where('campus', $campus)->count();
        
        if($affectedRows) {
            return response()->json([
                'success' => true,
                'activeEventsCount' => $activeEventsCount,
                'pendingEventsCount' => $pendingEventsCount,
                'rejectedEventsCount' => $rejectedEventsCount,
                'planningEventsCount' => $planningEventsCount,
                'code' => 200
            ]);
        } else {
            return response()->json([
                'error' => 'Internal server error.',
                'code' => 500
            ]);
        }
    }

    public function rejectEvent(Request $request)
    {
        $campus = session('campus');
        $reject = Event::where('id', $request->id)->update([
            'status' => 'Inactive'
        ]);  
        
        $pendingEventsCount = Event::where('status', 'Pending')->where('campus', $campus)->count();
        $planningEventsCount = Event::where('status', 'Planning')->where('campus', $campus)->count();
        $activeEventsCount = Event::where('status', 'Active')->where('campus', $campus)->count();
        $rejectedEventsCount = Event::where('status', 'Inactive')->where('campus', $campus)->count();
        
        if($reject) {
            return response()->json([
                'success' => true,
                'activeEventsCount' => $activeEventsCount,
                'pendingEventsCount' => $pendingEventsCount,
                'rejectedEventsCount' => $rejectedEventsCount,
                'planningEventsCount' => $planningEventsCount,
                'code' => 200
            ]);
        } else {
            return response()->json([
                'error' => 'Internal server error.',
                'code' => 500
            ]);
        }
    }

    public function destroy(Request $request)
    {
        $delete = Event::where('id', $request->id)->delete();

        if($delete) {
            return response()->json([
                'success' => true,
                'code' => 200
            ]);
        } else {
            return response()->json([
                'error' => 'Internal server error.',
                'code' => 500
            ]);
        }
    }
}
