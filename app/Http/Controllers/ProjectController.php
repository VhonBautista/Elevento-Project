<?php

namespace App\Http\Controllers;

use App\Models\EventType;
use App\Models\PlanningLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProjectController extends Controller
{
    public function index()
    {
        // ALL PLANNING PROJECTS
        $user = Auth::user();
        $query = $user->projects()->with('user', 'event', 'event.creator');
    
        // Filter by event type
        if ($eventType = request('eventType')) {
            $query->whereHas('event', function ($q) use ($eventType) {
                $q->where('event_type', $eventType);
            });
        }
    
        // Filter by target audience
        if ($targetAudience = request('targetAudience')) {
            $query->whereHas('event', function ($q) use ($targetAudience) {
                $q->where('target_audience', $targetAudience);
            });
        }
    
        // Search by event title or creator's username
        if ($search = request('search')) {
            $query->where(function ($q) use ($search) {
                $q->whereHas('event', function ($subq) use ($search) {
                    $subq->where('title', 'like', '%' . $search . '%');
                })->orWhereHas('user', function ($subq) use ($search) {
                    $subq->where('username', 'like', '%' . $search . '%');
                });
            });
        }

        // Filter by event status
        $query->whereHas('event', function ($q) {
            $q->where('status', 'planning');
        });
    
        $projects = $query->get();

        // ALL PLANNING PROJECTS
        $activeProjects = $user->projects()
            ->whereHas('event', function ($q) {
                $q->where('status', 'active');
            })
            ->with('user', 'event', 'event.creator')
            ->get();

        // ALL PENDING PROJECTS
        $approvalProjects = $user->projects()
            ->whereHas('event', function ($q) {
                $q->where('status', 'pending');
            })
            ->with('user', 'event', 'event.creator')
            ->get();

        // EVENT TYPES
        $eventTypes = EventType::all();

        return view('projects', ['projects' => $projects, 'activeProjects' => $activeProjects, 'approvalProjects' => $approvalProjects, 'eventTypes' => $eventTypes, 'user' => $user]);
    }
}
