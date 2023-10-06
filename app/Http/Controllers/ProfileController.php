<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Organization;
use App\Models\UpgradeRequest;

class ProfileController extends Controller
{
    /**
     * Edit the user's profile.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $user = User::with('campusEntity')->find($id);
        
        if (!$user) {
            abort(404);
        }

        $campus = $user->campusEntity->campus;;
        $organizations = Organization::where('campus', $campus)->orderBy('organization')->get();


        return view('profile', ['user' => $user, 'organizations' => $organizations]);
    }

    /**
     * Update the user's profile.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'email' => 'required|email',
            'username' => 'required|string|max:255',
        ]);

        if ($request['email'] != Auth::user()->email) {
            $existingEmail = User::where('email', $request['email'])->first();
    
            if ($existingEmail) {
                return redirect()->back()->with('error-email', 'Email has already been taken.');
            }
        }
        
        $user = User::find($id);
        if (!$user) {
            abort(404);
        }
        
        $user->email = $request->input('email');
        $user->username = $request->input('username');
        
        $user->save();

        return redirect()->back()->with('success-account', 'Your account details has been updated successfully.');
    }

    public function upload(Request $request)
    {
        $request->validate([
            'photo' => 'required|image|mimes:jpeg,png,jpg,gif',
        ]);
        
        $photo = $request->file('photo');
        $fileName = time() . '.' . $photo->getClientOriginalExtension();
        $photo->move(public_path('profile'), $fileName);
        $user = Auth::user();
        $user->profile_picture = 'profile/' . $fileName;
        $user->save();

        return redirect()->back()->with('success-account', 'Your profile picture has been updated successfully.');
    }

    public function changePassword(Request $request, $id)
    {
        $request->validate([
            'current_password' => 'required|string|max:255',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $newPassword = $request->input('password');
        $confirmPassword = $request->input('password_confirmation');

        $user = User::find($id);
        if (!$user) {
            abort(404);
        }
        
        if ($request->input('current_password') != "") {
            if (!Hash::check($request->input('current_password'), $user->password)) {
                return redirect()->back()->with('error-current', 'The entered password is incorrect.');
            }
            
            $user->password = Hash::make($request->input('password'));
        }

        $user->save();

        return redirect()->back()->with('success-password', 'Your password has been updated successfully.');
    }

    public function createRequest(Request $request)
    {
        $id = Auth::id();
        $organization = $request->input('organization');

        $existingRequest = UpgradeRequest::where('user_id', $id)->exists();

        if ($existingRequest) {
            return redirect()->back()->with(['existing-request' => 'You can only send one request at a time. Please wait until the admin reviews your request.']);
        }

        $request->validate([
            'organization' => 'required',
        ]);

        UpgradeRequest::create([
            'user_id' => $id,
            'organization_id' => $organization,
        ]);

        return redirect()->back()->with('success-request', 'Your request has been sent successfully.');
    }
}
