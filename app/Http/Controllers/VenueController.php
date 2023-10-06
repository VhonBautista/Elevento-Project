<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Venue;

class VenueController extends Controller
{
    public function getVenues()
    {
        $campus = session('campus');
        $venues = Venue::select(
            'id',
            'image',
            'venue_name',
            'handler_name',
            'capacity',
            'campus',
            'status'
            )
        ->where('campus', $campus)
        ->orderBy('updated_at', 'desc')
        ->get();
        
        if ($venues) {
            return response()->json([
                'message' => 'Data is present.',
                'code' => 200,
                'data' => $venues
            ]);
        } else {
            return response()->json([
                'message' => 'Internal server error.',
                'code' => 500
            ]);
        }
    }

    public function getSelectedVenue(Request $request)
    {
        $venue = Venue::where('id', $request->id)->first();

        if ($venue){
            return response()->json([
                'success' => true, 
                'code' => 200, 
                'data' => $venue
            ]);
        } else {
            return response()->json([
                'error' => 'Internal server error.',
                'code' => 500
            ]);
        }
    }

    public function updateVenue(Request $request)
    {
        $imageData = Venue::where('id', $request->selected_venue_id)->pluck('image')->first();

        $photo = $request->file('update_photo_venue');
        $fileName = $imageData;

        if ($photo) {
            $fileName = time() . '.' . $photo->getClientOriginalExtension();
            $photo->move(public_path('venue'), $fileName);
            $fileName = 'venue/' . $fileName;
        }

        $venue = Venue::where('id', $request->selected_venue_id)->update([
            'venue_name' => $request->update_venue,
            'handler_name' => $request->update_handler,
            'capacity' => $request->update_capacity,
            'image' => $fileName
        ]);        

        if ($venue){
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

    public function updateStatus(Request $request)
    {
        $venue = Venue::where('id', $request->id)->first();
        if ($venue->status == "Active"){
            $venue->status = "Inactive";
        } else {
            $venue->status = "Active";
        }
        $venue->save();

        if($venue) {
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

    public function store(Request $request)
    {
        $request->validate([
            'photo_venue' => 'image|mimes:jpeg,png,jpg,gif',
        ]);

        $campus = session('campus');
        $venueInput = ucwords(trim($request->venue));
        $handlerInput = ucwords(trim($request->handler));
        $capacityInput = $request->capacity;

        if (!$venueInput) {
            return response()->json(['validate' => 'Venue name field is required.']);
        }
        
        $photo = $request->file('photo_venue');
        $fileName = null;

        if ($photo) {
            $fileName = time() . '.' . $photo->getClientOriginalExtension();
            $photo->move(public_path('venue'), $fileName);
            $fileName = 'venue/' . $fileName;
        }
        
        $venue = new Venue; 
        $venue->venue_name = $venueInput;
        $venue->handler_name = $handlerInput;
        $venue->capacity = $capacityInput;
        $venue->image = $fileName;
        $venue->campus = $campus;

        $result = $venue->save();
        
        if($result) {
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

    public function destroy(Request $request)
    {
        $venue = Venue::where('id', $request->id)->delete();

        if($venue) {
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
