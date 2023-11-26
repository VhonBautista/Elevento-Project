<?php

namespace App\Http\Controllers;

use App\Models\EventImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class EventController extends Controller
{
  public function uploadImages(Request $request, $eventId)
  {
      $request->validate([
          'images.*' => 'image|mimes:jpeg,png,jpg,gif',
      ]);
  
      $existingImages = EventImage::where('event_id', $eventId)->get();

      foreach ($existingImages as $existingImage) {
          $oldImagePath = public_path($existingImage->path);

          if (file_exists($oldImagePath)) {
              unlink($oldImagePath);
          }
          $existingImage->delete();
      }

      foreach ($request->file('images') as $image) {
          $fileName = uniqid() . '.' . $image->getClientOriginalExtension();

          $image->move(public_path('event_images'), $fileName);
          EventImage::create([
              'event_id' => $eventId,
              'path' => 'event_images/' . $fileName,
          ]);
      }
  
      return response()->json(['message' => 'Images uploaded successfully.']);
  }
}
