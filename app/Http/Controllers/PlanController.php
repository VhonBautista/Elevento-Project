<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\EventPeople;
use App\Models\Flow;
use App\Models\PlanningLog;
use App\Models\Project;
use App\Models\ScheduleRequest;
use App\Models\Segment;
use App\Models\User;
use App\Notifications\AllNotification;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Notification;

class PlanController extends Controller
{
    public function index($eventId)
    {
        $user = Auth::user();

        $event = Event::with('venue')->with('peoples')->with('images')->with('segments.flows')->findOrFail($eventId);
        $segments = $event->segments;
        $peoples = $event->peoples;
        $images = $event->images;

        $project = Project::where('user_id', $user->id)
        ->where('event_id', $eventId)
        ->first();

        $projectUsers = Project::with('user')->where('event_id', $eventId)->get();
        
        // LOGS
        $projectLogs = PlanningLog::where('event_id', $eventId)
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        if (!$event) {
            abort(404, 'Event not found');
        }
        
        return view('plan', ['event' => $event, 'images' => $images, 'segments' => $segments, 'peoples' => $peoples, 'project' => $project, 'projectUsers' => $projectUsers, 'projectLogs' => $projectLogs, 'user' => $user]);
    }

    public function deleteEvent(Request $request)
    {
        $eventId = $request->input('event_id');

        $event = Event::find($eventId);

        if (!$event) {
            abort(404);
        }

        // Notify all favoritedBy of the event:
        $favoritedUsers = $event->registrations()->with('user')->get()->pluck('user')->unique();
        foreach ($favoritedUsers as $user) {
            $title = 'Event Deleted';
            $type = 'Deleted';
            $message = 'The event "' . $event->title . '" has been removed by the creator of the event.';
            $url = '/';

            Notification::send($user, new AllNotification($title, $type, $message, $url));
        }

        // Detach associated projects
        $event->projects()->detach();

        // Delete associated reschedule request
        $event->rescheduleRequest()->delete();

        // Delete associated flows (ensure flows are deleted before segments)
        $event->segments()->each(function ($segment) {
            $segment->flows()->delete();
        });

        // Delete associated segments
        $event->segments()->delete();

        // Delete other associated records
        $event->peoples()->delete();
        $event->favorites()->delete();
        $event->images()->delete();
        $event->planningLogs()->delete();
        $event->registrations()->delete();

        $event->delete();

        return redirect()->route('projects')->with('success', 'Event deleted successfully');
    }

    public function updateDescription(Request $request, Event $event)
    {
        $request->validate([
            'description' => 'required|string',
        ]);

        $event->update([
            'description' => $request->input('description'),
        ]);

        // Planning Log
        $user = Auth::user();
        $description = $user->username . ' just updated the event description.';
        $planningLog = new PlanningLog([
            'event_id' => $event->id,
            'status' => 'updated',
            'description' =>  $description,
        ]);
        $user->planningLog()->save($planningLog);

        // Notify project creator

        return response()->json(['description' => $event->description]);
    }

    public function updateStatus(Request $request, Event $event)
    {
        $status = $event->status == 'Planning' ? 'Active' : 'Planning';
        $event->update([
            'status' => $status,
        ]);

        // Planning Log
        $user = Auth::user();
        $description = $user->username . ' just set the event to ' . $status ;
        $planningLog = new PlanningLog([
            'event_id' => $event->id,
            'status' => 'updated',
            'description' =>  $description,
        ]);
        $user->planningLog()->save($planningLog);

        $creator = User::findOrFail($event->creator_id);

        // Notification
        if ($user->id !== $event->creator_id) {
            $title = 'Event Status Updated ';
            $type = 'Updated';
            $message = $user->username . ' updated the event\'s status.';
            $url = '/events/plan/' . $event->id;

            Notification::send($creator, new AllNotification($title, $type, $message, $url));
        }

        return redirect()->back();
    }

    public function reschedule(Request $request)
    {
        $validatedData = $request->validate([
            'event_id' => 'required',
            'event_start' => 'required|date',
            'event_end' => 'required|date|after_or_equal:event_start',
            'reason' => 'required|string',
        ]);
        
        $eventId = $validatedData['event_id'];
        
        // Check if a record already exists for the given eventId
        $existingReschedule = ScheduleRequest::where('event_id', $eventId)->first();

        if ($existingReschedule) {
            $startDateTime = new DateTime($existingReschedule->new_start);
            $formattedStartDate = $startDateTime->format('M d, Y');
            $endDateTime = new DateTime($existingReschedule->new_end);
            $formattedEndDate = $endDateTime->format('M d, Y');

            return response()->json([
                'error' => 'There is already a reschedule request in place for the event, scheduled for ' . $formattedStartDate . ' to  ' . $formattedEndDate . '. Please await administrator for review and handling.',
                'code' => 500
            ]);
        }
        
        $start = $validatedData['event_start'];
        $startDateTime = new DateTime($start);
        $formattedStartDate = $startDateTime->format('M d, Y');
        
        $end = $validatedData['event_end'];
        $endDateTime = new DateTime($end);
        $formattedEndDate = $endDateTime->format('M d, Y');

        $reason = $validatedData['reason'];

        $reschedule = ScheduleRequest::create([
            'event_id' => $eventId,
            'new_start' => $start,
            'new_end' => $end,
            'reason' => $reason,
        ]);

        // Planning Log
        $user = Auth::user();
        $description = $user->username . ' has submitted a request to reschedule the event from ' . $formattedStartDate  . ' to ' . $formattedEndDate  . '.';
        $planningLog = new PlanningLog([
            'event_id' => $eventId,
            'status' => 'rescheduled',
            'description' =>  $description,
        ]);
        $user->planningLog()->save($planningLog);

        if($reschedule) {
            return response()->json([
                'success' => 'Reschedule request was sent successfully.',
                'code' => 200,
            ]);
        } else {
            return response()->json([
                'error' => 'Internal server error.',
                'code' => 500
            ]);
        }
    }

    // MANAGE SEGMENT AND FLOW
    public function addFlow(Request $request)
    {
        $validatedData = $request->validate([
            'event_id' => 'required',
            'segment_id' => 'required',
            'time_of_day' => 'required|string',
            'time_start' => 'required|date_format:H:i',
            'time_end' => 'required|date_format:H:i|after_or_equal:time_start',
            'flow' => 'required|string',
        ]);
        
        // Check for duplicate segment
        $eventId = $validatedData['event_id'];
        $segmentId = $validatedData['segment_id'];
        $typeOfDay = $validatedData['time_of_day'];
        $timeStart = $validatedData['time_start'];
        $timeEnd = $validatedData['time_end'];
        $programFlow = $validatedData['flow'];

        if (Flow::where('segment_id', $segmentId)->where('timeline', $typeOfDay)->exists()) {
            return response()->json([
                'error' => 'There is already an existing ' . $typeOfDay . ' Flow for this day.',
            ]);
        }

        switch ($typeOfDay) {
            case 'Morning':
                if (!$this->isTimeInRange($timeStart, '06:00', '12:00') || !$this->isTimeInRange($timeEnd, '06:00', '12:00')) {
                    return response()->json([
                        'error' => $typeOfDay . ' must be only between 6:00 AM to 12:00 PM.',
                    ]);
                }
                break;
        
            case 'Noon':
                if (!$this->isTimeInRange($timeStart, '12:00', '13:00') || !$this->isTimeInRange($timeEnd, '12:00', '13:00')) {
                    return response()->json([
                        'error' => $typeOfDay . ' must be only between 12:00 PM to 01:00 PM.',
                    ]);
                }
                break;
        
            case 'Afternoon':
                if (!$this->isTimeInRange($timeStart, '13:00', '18:00') || !$this->isTimeInRange($timeEnd, '13:00', '18:00')) {
                    return response()->json([
                        'error' => $typeOfDay . ' must be only between 1:00 PM to 06:00 PM.',
                    ]);
                }
                break;
        
            case 'Evening':
                if (!$this->isTimeInRange($timeStart, '18:00', '23:59') || !$this->isTimeInRange($timeEnd, '18:00', '23:59')) {
                    return response()->json([
                        'error' => $typeOfDay . ' must be only between 6:00 PM to 11:59 PM.',
                    ]);
                }
                break;
        
            case 'Midnight':
                if (!$this->isTimeInRange($timeStart, '00:00', '06:00') || !$this->isTimeInRange($timeEnd, '00:00', '06:00')) {
                    return response()->json([
                        'error' => $typeOfDay . ' must be only between 12:00 AM to 06:00 AM.',
                    ]);
                }
                break;
        
            default:
                return response()->json([
                    'error' => 'Something went wrong in the server. Contact admin ASAP.',
                ]);
                break;
        }        

        // create flow
        $newFlow = Flow::create([
            'segment_id' => $segmentId,
            'timeline' => $typeOfDay,
            'start_time' => $timeStart,
            'end_time' => $timeEnd,
            'list' => $programFlow,
        ]);

        $segment = Segment::findOrFail($segmentId);

        // Planning Log
        $user = Auth::user();
        $carbonDate = \Carbon\Carbon::parse($segment->date);
        $formattedDate = $carbonDate->format('F j, Y (l)');
        $description = $user->username . ' has added a new flow type \'' . $typeOfDay  . '\' to segment of (' . $formattedDate . ').';
        $planningLog = new PlanningLog([
            'event_id' => $eventId,
            'status' => 'created',
            'description' =>  $description,
        ]);
        $user->planningLog()->save($planningLog);

        if($newFlow) {
            return response()->json([
                'success' => 'New flow has been created successfully.',
            ]);
        } else {
            return response()->json([
                'error' => 'Something went wrong in the server. Contact admin ASAP.',
            ]);
        }
    }

    public function updateFlow(Request $request)
    {
        $validatedData = $request->validate([
            'event_id' => 'required',
            'segment_id' => 'required',
            'flow_id' => 'required',
            'new_flow' => 'required',
        ]);
        
        $eventId = $validatedData['event_id'];
        $segmentId = $validatedData['segment_id'];
        $flowId = $validatedData['flow_id'];
        $newFlow = $validatedData['new_flow'];
        
        $affectedRows = Flow::where('id', $flowId)->update([
            'list' => $newFlow,
        ]);

        $flow = Flow::find($flowId);

        if ($affectedRows && $flow) {
            $segment = Segment::findOrFail($segmentId);

            // Planning Log
            $user = Auth::user();
            $carbonDate = \Carbon\Carbon::parse($segment->date);
            $formattedDate = $carbonDate->format('F j, Y (l)');
            $description = $user->username . ' updated the flow type \'' . $flow->timeline  . '\' from segment of (' . $formattedDate . ').';
            $planningLog = new PlanningLog([
                'event_id' => $eventId,
                'status' => 'created',
                'description' =>  $description,
            ]);
            $user->planningLog()->save($planningLog);

            return response()->json(['success' => 'Flow updated successfully']);
        } else {
            return response()->json(['error' => 'Flow not found or not updated'], 404);
        }
    }

    public function deleteFlow($eventId, $segmentId, $flowId)
    {
        $flow = Flow::findOrFail($flowId);
        $segment = Segment::findOrFail($segmentId);

        // Planning Log
        $user = Auth::user();
        $carbonDate = \Carbon\Carbon::parse($segment->date);
        $formattedDate = $carbonDate->format('F j, Y (l)');
        $description = $user->username . ' deleted the flow \'' . $flow->timeline  . '\' to segment of (' . $formattedDate . ').';
        $planningLog = new PlanningLog([
            'event_id' => $eventId,
            'status' => 'deleted',
            'description' =>  $description,
        ]);
        $user->planningLog()->save($planningLog);

        Flow::destroy($flowId);

        if($eventId) {
            return redirect()->route('plan', ['eventId' => $eventId])->with('success', 'Flow has been deleted successfully.');
        } else {
            return redirect()->route('plan', ['eventId' => $eventId])->with('error', 'Something went wrong.');
        }
    }

    /**
     * Check if a given time is in the specified range.
     *
     * @param string $time
     * @param string $startTime
     * @param string $endTime
     * @return bool
     */
    private function isTimeInRange($time, $startTime, $endTime)
    {
        return strtotime($time) >= strtotime($startTime) && strtotime($time) <= strtotime($endTime);
    }

    // MANAGE EVENT PEOPLE
    public function addPerson(Request $request)
    {
        $validatedData = $request->validate([
            'event_id' => 'required',
            'name' => 'required|string',
            'title' => 'nullable|string',
            'role' => 'required|string',
        ]);
        
        $eventId = $validatedData['event_id'];
        $name = $validatedData['name'];
        $title = $validatedData['title'];
        $role = $validatedData['role'];

        // create event people
        $newPerson = EventPeople::create([
            'event_id' => $eventId,
            'name' => $name,
            'title' => $title,
            'role' => $role,
        ]);

        // Planning Log
        $user = Auth::user();
        $description = $user->username . ' added ' . $newPerson->name . ' as ' . $newPerson->role . ' for this event.';
        $planningLog = new PlanningLog([
            'event_id' => $eventId,
            'status' => 'created',
            'description' =>  $description,
        ]);
        $user->planningLog()->save($planningLog);

        if($newPerson) {
            return response()->json([
                'success' => 'New event person has been added successfully.',
            ]);
        } else {
            return response()->json([
                'error' => 'Something went wrong in the server. Contact admin ASAP.',
            ]);
        }
    }
    
    public function updatePerson(Request $request)
    {
        $validatedData = $request->validate([
            'event_id' => 'required',
            'person_id' => 'required',
            'name' => 'required|string',
            'role' => 'required|string',
            'title' => 'nullable',
        ]);
        
        $eventId = $validatedData['event_id'];
        $personId = $validatedData['person_id'];
        $name = $validatedData['name'];
        $role = $validatedData['role'];
        $title = $validatedData['title'];
        
        $affectedRows = EventPeople::where('id', $personId)->update([
            'name' => $name,
            'role' => $role,
            'title' => $title,
        ]);

        $person = EventPeople::find($personId);

        if ($affectedRows && $person) {
            // Planning Log
            $user = Auth::user();
            $description = $user->username . ' updated the details of event person \'' . $person->name  . '\'.';
            $planningLog = new PlanningLog([
                'event_id' => $eventId,
                'status' => 'updated',
                'description' =>  $description,
            ]);
            $user->planningLog()->save($planningLog);

            return response()->json(['success' => 'Person updated successfully']);
        } else {
            return response()->json(['error' => 'Person not found or not updated'], 404);
        }
    }

    public function deletePerson($eventId, $personId)
    {
        $person = EventPeople::findOrFail($personId);

        // Planning Log
        $user = Auth::user();
        $description = $user->username . ' removed ' . $person->name . ' form the event people and guests.';
        $planningLog = new PlanningLog([
            'event_id' => $eventId,
            'status' => 'deleted',
            'description' =>  $description,
        ]);
        $user->planningLog()->save($planningLog);

        EventPeople::destroy($personId);

        if($eventId) {
            return redirect()->route('plan', ['eventId' => $eventId])->with('person-success', 'Event person has been deleted successfully.');
        } else {
            return redirect()->route('plan', ['eventId' => $eventId])->with('person-error', 'Something went wrong.');
        }
    }

    public function searchUsers(Request $request)
    {
        $searchTerm = $request->input('searchTerm');
        $projectId = $request->input('projectId');

        $users = User::where('email', 'like', '%' . $searchTerm . '%')
            ->whereNotIn('role', ['User'])
            ->whereDoesntHave('projects', function ($query) use ($projectId) {
                $query->where('id', $projectId);
            })
            ->get();

        return response()->json(['users' => $users]);
    }

    public function addUser(Request $request)
    {   
        $userId = $request['userId'];
        $eventId = $request['eventId'];

        // create new people project
        $newProject = Project::create([
            'user_id' => $userId,
            'event_id' => $eventId,
            'role' => 'member',
        ]);

        // Notification
        $creator = Auth::user();
        $user = User::find($userId);
        $event = Event::find($eventId);
        $title = 'Invited to Project';
        $type = 'Created';
        $message = $creator->username . ' just added you as a member to the project ' . $event->title . '.';
        $url = '/events/plan/' . $event->id;

        Notification::send($user, new AllNotification($title, $type, $message, $url));


        if($newProject) {
            return response()->json([
                'success' => 'New user has been added to this project successfully.',
            ]);
        } else {
            return response()->json([
                'error' => 'Something went wrong in the server. Contact admin ASAP.',
            ]);
        }
    }

    public function removeUser(Request $request)
    {   
        $projectId = $request['projectId'];
        $eventId = $request['eventId'];

        $creatorProject = Project::where('event_id', $eventId)
            ->where('role', 'creator')
            ->with('user')
            ->first();

        // // Notification
        $project = Project::find($projectId);
        $user = User::find($project->user_id);
        $event = Event::find($eventId);
        $title = 'Removed from Project';
        $type = 'Deleted';
        $message = $creatorProject->user->username . ' removed you from the project ' . $event->title . '.';
        $url = '/';

        Notification::send($user, new AllNotification($title, $type, $message, $url));

        Project::destroy($projectId);

        return response()->json([
            'success' => 'A user has been removed from the project successfully.',
        ]);
    }

    public function upload(Request $request)
    {
        $request->validate([
            'event_id' => 'required',
            'photo' => 'required|image|mimes:jpeg,png,jpg,gif',
        ]);

        $photo = $request->file('photo');
        $eventId = $request->event_id;

        $fileName = time() . '.' . $photo->getClientOriginalExtension();
        $photo->move(public_path('covers'), $fileName);

        $event = Event::find($eventId);

        if ($event) {
            if ($event->cover_photo) {
                $oldCoverPhotoPath = public_path($event->cover_photo);
                if (file_exists($oldCoverPhotoPath)) {
                    unlink($oldCoverPhotoPath);
                }
            }

            $event->cover_photo = 'covers/' . $fileName;
            $event->save();

            // Planning Log
            $user = Auth::user();
            $description = $user->username . ' changed the event\'s cover photo.';
            $planningLog = new PlanningLog([
                'event_id' => $eventId,
                'status' => 'updated',
                'description' =>  $description,
            ]);
            $user->planningLog()->save($planningLog);

            $creator = User::findOrFail($event->creator_id);

            // Notification
            if ($user->id !== $event->creator_id) {
                $title = 'Event Cover Photo Updated ';
                $type = 'Updated';
                $message = $user->username . ' updated the event\'s cover photo.';
                $url = '/events/plan/' . $event->id;

                Notification::send($creator, new AllNotification($title, $type, $message, $url));
            }

            return redirect()->back()->with('success-cover', 'Event cover picture has been updated successfully.');
        } else {
            return redirect()->back()->with('error-cover', 'Event not found.');
        }
    }
}
