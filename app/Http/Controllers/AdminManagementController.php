<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\UpgradeRequest;
use App\Models\CampusEntity;
use App\Models\Department;
use App\Models\Event;
use App\Models\Project;
use App\Models\Segment;
use App\Models\User;
use Carbon\Carbon;

class AdminManagementController extends Controller
{
    public function index()
    {
        $campus = session('campus');

        // $users = User::whereHas('campusEntity', function($query) use ($campus) {
        //     $query->where('campus', $campus);
        // })->with(['campusEntity', 'organization'])->get();

        $departments = Department::select(
            'department_code',
            'department',
            'campus',
            )
        ->where('campus', $campus)
        ->orderBy('department')
        ->get();

        // return view('user_admin.management', ['usersData' => $users, 'departmentsData' => $departments]);
        return view('user_admin.management', ['departmentsData' => $departments]);
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'event_title' => 'required|string|max:255',
            'event_start' => 'required|date',
            'event_end' => 'required|date|after_or_equal:event_start',
            'entity_type' => 'required|string',
            'target_audience' => 'required|string',
            'venue' => 'required|string',
        ]);

        $title = $validatedData['event_title'];
        $start = $validatedData['event_start'];
        $end = $validatedData['event_end'];
        $type = $validatedData['entity_type'];
        $audience = $validatedData['target_audience'];
        $venue = $validatedData['venue'];
        $userId = Auth::id();
        $campus = session('campus');

        if ($start < now()) {
            return response()->json([
                'error' => 'Start date cannot be earlier than today.',
                'code' => 500
            ]);
        }        

        if ($start > $end) {
            return response()->json([
                'error' => 'End date cannot be before the start date.',
                'code' => 500
            ]);
        }        

        $count = Event::where('venue_id', $venue)
        ->where(function ($query) use ($start, $end) {
            $query->whereBetween('start_date', [$start, $end])
                ->orWhereBetween('end_date', [$start, $end])
                ->orWhere(function ($query) use ($start, $end) {
                    $query->where('start_date', '<=', $start)
                        ->where('end_date', '>=', $end);
                });
        })
        ->where(function ($query) {
            $query->where('status', 'planning')
                ->orWhere('status', 'active');
        })
        ->count();


        if ($count > 0) {
            return response()->json([
                'error' => 'The venue has already been reserved for the chosen date, either by events currently in the planning stage or by events that are currently active.',
                'code' => 500
            ]);
        }

        $event = Event::create([
            'status' => 'Planning',
            'title' => $title,
            'start_date' => $start,
            'end_date' => $end,
            'creator_id' => $userId,
            'campus' => $campus,
            'venue_id' => $venue,
            'event_type' => $type,
            'target_audience' => $audience,
        ]);

        if ($event->status === 'Planning') {
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

        $project = Project::create([
            'user_id' => $userId,
            'event_id' => $event->id,
            'role' => 'creator',
        ]);

        if ($event && $project) {
            return response()->json([
                'success' => 'Event created successfuly',
                'eventId' => $event->id,
                'code' => 500
            ]);
        } else {
            return response()->json([
                'error' => 'Internal server error.',
                'code' => 500
            ]);
        }

        // for organizers
        // $create = Event::create([
        //     'title' => $title,
        //     'start_date' => $start,
        //     'end_date' => $end,
        //     'creator_id' => $userId,
        //     'campus' => $campus,
        //     'venue_id' => $venue,
        //     'event_type' => $type,
        //     'target_audience' => $audience,
        // ]);
    }
    
    public function getAdmins()
    {
        $campus = session('campus');
        $admins = User::select(
            'users.id',
            'users.user_id',
            'users.role',
            'users.email',
            'users.profile_picture',
            'users.username',
            'users.manage_user',
            'users.manage_venue',
            'users.manage_campus',
            'users.manage_event',
            'campus_entities.department_code'
            )
        ->join('campus_entities', 'users.user_id', '=', 'campus_entities.user_id')
        ->where('campus_entities.campus', $campus)
        ->where('users.role', 'Co-Admin')
        ->orderBy('users.username')
        ->get();
        
        if ($admins) {
            return response()->json([
                'message' => 'Data is present.',
                'code' => 200,
                'data' => $admins
            ]);
        } else {
            return response()->json([
                'message' => 'Internal server error.',
                'code' => 500
            ]);
        }
    }

    public function getOrganizers()
    {
        $campus = session('campus');
        $organizers = User::select(
            'users.id',
            'users.user_id',
            'users.email',
            'users.profile_picture',
            'users.username',
            'departments.department',
            'campus_entities.department_code',
            'campus_entities.type'
            )
        ->join('campus_entities', 'users.user_id', '=', 'campus_entities.user_id')
        ->join('departments', 'campus_entities.department_code', '=', 'departments.department_code')
        ->where('campus_entities.campus', $campus)
        ->where('users.role', 'Organizer')
        ->where('campus_entities.type', 'Employee')
        ->orderBy('users.username')
        ->get();
        
        if ($organizers) {
            return response()->json([
                'message' => 'Data is present.',
                'code' => 200,
                'data' => $organizers
            ]);
        } else {
            return response()->json([
                'message' => 'Internal server error.',
                'code' => 500
            ]);
        }
    }

    public function markRead(Request $request)
    {
        auth()->user()
            ->unreadNotifications
            ->when($request->input('id'), function ($query) use ($request) {
                return $query->where('id', $request->input('id'));
            })->markAsRead();
    
        return response()->noContent();
    }

    public function getRequests()
    {
        $campus = session('campus');
        $requests = UpgradeRequest::select(
            'requests.id',
            'requests.status',
            'requests.message',
            'departments.department',
            'organizations.organization',
            'users.id as userId',
            'users.user_id',
            'users.email',
            'users.profile_picture',
            'users.username',
            'campus_entities.type',
            'campus_entities.department_code'
            )
        ->join('users', 'requests.user_id', '=', 'users.id')
        ->join('campus_entities', 'users.user_id', '=', 'campus_entities.user_id')
        ->join('departments', 'campus_entities.department_code', '=', 'departments.department_code')
        ->join('organizations', 'requests.organization_id', '=', 'organizations.id')
        ->where('campus_entities.campus', $campus)
        ->where('requests.status', 'Pending')
        ->orderBy('requests.created_at')
        ->get();
        
        if ($requests) {
            return response()->json([
                'message' => 'Data is present.',
                'code' => 200,
                'data' => $requests
            ]);
        } else {
            return response()->json([
                'message' => 'Internal server error.',
                'code' => 500,
            ]);
        }
    }
    
    public function getUsers()
    {
        $campus = session('campus');
        $users = User::select(
            'users.id', 
            'users.user_id', 
            'users.role', 
            'users.email', 
            'users.profile_picture', 
            'users.username', 
            'users.is_disabled', 
            'campus_entities.sex', 
            'campus_entities.type', 
            'campus_entities.department_code',
            'organizations.organization'
        )
        ->leftJoin('organizations', 'users.organization_id', '=', 'organizations.id')
        ->join('campus_entities', 'users.user_id', '=', 'campus_entities.user_id')
        ->where('campus_entities.campus',  $campus)
        ->where(function($query) {
            $query->where('users.role', 'Organizer')
                ->orWhere('users.role', 'User')
                ->orWhere('users.role', 'Co-Admin');
        })
        ->orderBy('users.username')
        ->get();    
        
        if ($users->isNotEmpty()) {
            return response()->json([
                'message' => 'Data is present.',
                'code' => 200,
                'data' => $users
            ]);
        } else {
            dd($users);
            return response()->json([
                'message' => 'Internal server error.',
                'code' => 500
            ]);
        }
    }

    public function getPermission(Request $request)
    {
        $user = User::where('id', $request->id)->first();
        if ($user){
            return response()->json([
                'success' => true, 
                'code' => 200, 
                'data' => $user
            ]);
        } else {
            return response()->json([
                'error' => 'Internal server error.',
                'code' => 500
            ]);
        }
    }

    public function updatePermission(Request $request)
    {
        $user = User::where('id', $request->selected_id)->update([
            'manage_user' => $request->has('update_manage_user') ? 1 : 0,
            'manage_venue' => $request->has('update_manage_venue') ? 1 : 0,
            'manage_campus' => $request->has('update_manage_campus') ? 1 : 0,
            'manage_event' => $request->has('update_manage_event') ? 1 : 0,
        ]);        

        if ($user){
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

    public function promoteToCoAdmin(Request $request)
    {
        $user = User::where('id', $request->id)->update([
            'role' => 'Co-Admin'
        ]);     

        if($user) {
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

    public function promoteToOrganizer(Request $request)
    {
        $updateRequest = UpgradeRequest::where('id', $request->id)->update([
            'status' => 'Active'
        ]);  
        
        $user = User::where('id', $request->userId)->update([
            'role' => 'Organizer'
        ]);     

        if($updateRequest && $user) {
            return response()->json([
                'success' => true,
                'code' => 200,
                'user' => $request->userId,
                'request' => $request->id
            ]);
        } else {
            return response()->json([
                'error' => 'Internal server error.',
                'code' => 500,
                'user' => $request->userId,
                'request' => $request->id
            ]);
        }
    }

    public function rejectRequest(Request $request)
    {
        $rejected = UpgradeRequest::where('id', $request->rejected_id)->update([
            'status' => 'Inactive',
            'message' => $request->message
        ]);     

        if($rejected) {
            return response()->json([
                'success' => 'A new venue has been successfully added.',
                'code' => 200
            ]);
        } else {
            return response()->json([
                'error' => 'Internal server error.',
                'code' => 500
            ]);
        }
    }

    public function demote(Request $request)
    {
        $user = User::where('id', $request->id)->update([
            'role' => 'Organizer',
            'manage_user' => 0,
            'manage_venue' => 0,
            'manage_campus' => 0,
            'manage_event' => 0
        ]);     

        if($user) {
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

    public function updateIsDisabled(Request $request)
    {
        $user = User::where('id', $request->id)->first();
        if ($user->is_disabled){
            $user->is_disabled = 0;
        } else {
            $user->is_disabled = 1;
        }
        $user->save();

        if($user) {
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
