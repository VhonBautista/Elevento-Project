<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Hash; 
use Illuminate\Http\Request;
use App\Models\CampusEntity;
use App\Models\User;

class AdminManagementController extends Controller
{
    public function index()
    {
        $campus = session('campus');
        $users = User::whereHas('campusEntity', function($query) use ($campus) {
            $query->where('campus', $campus);
        })->with(['campusEntity', 'organization'])->get();

        return view('user_admin.management', ['usersData' => $users]);
    }
    
    public function getUsers()
    {
        $campus = session('campus');
        $users = User::select(
            'users.id', 
            'users.role', 
            'users.email', 
            'users.profile_picture', 
            'users.username', 
            'users.isDisabled', 
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

    public function getAdmins()
    {
        $campus = session('campus');
        $admins = User::select(
            'users.id',
            'users.role',
            'users.email',
            'users.profile_picture',
            'users.username',
            'campus_entities.sex',
            'campus_entities.type'
            )
        ->join('campus_entities', 'users.user_id', '=', 'campus_entities.user_id')
        ->where('campus_entities.campus', $campus)
        ->where('users.role', 'Co-Admin')
        ->get();
        
        if ($admins->isNotEmpty()) {
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

    public function store(Request $request)
    {
        $inputUserId = strtoupper(trim($request->user_id));

        $campusEntity = CampusEntity::where('user_id', $inputUserId)->first();

        if (!$campusEntity) {
            return response()->json(['errorRegister' => 'Ensure that you belong to PSU and that your user ID is valid.']);
        }

        $existingUser = User::where('user_id', $inputUserId)->first();

        if ($existingUser) {
            return response()->json(['errorRegister' => 'User ID already has an existing account.']);
        }

        $existingEmail = User::where('email', $request->email)->first();

        if ($existingEmail) {
            return response()->json(['errorRegister' => 'Email has already been taken. Please choose a different one.']);
        }
        
        $username = ucfirst(strtolower($campusEntity->firstname));

        $user = new User; 
        $user->role = 'Co-Admin';
        $user->user_id = $inputUserId;
        $user->username = $username;
        $user->email = $request->email;
        $user->password = Hash::make($request->password);
        $user->isDisabled = 0;

        $result = $user->save();
        
        if($result) {
            return response()->json([
                'successRegister' => 'Co-Administrator was created successfully',
                'code' => 200
            ]);
        } else {
            return response()->json([
                'errorRegister' => 'Internal server error.',
                'code' => 500
            ]);
        }
    }

    public function updateIsDisabled(Request $request)
    {
        $user = User::where('id', $request->id)->first();
        if ($user->isDisabled){
            $user->isDisabled = 0;
        } else {
            $user->isDisabled = 1;
        }
        $user->save();

        return response()->json(['success' => true, 'data' => $request->id]);
    }
}
