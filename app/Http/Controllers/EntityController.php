<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CampusEntity;
use App\Models\Department;
use App\Models\Organization;

class EntityController extends Controller
{
    public function getEntities()
    {
        $campus = session('campus');
        $entities = CampusEntity::select(
            'user_id',
            'firstname',
            'lastname',
            'middlename',
            'type',
            'sex',
            'campus',
            'department_code'
            )
        ->where('campus', $campus)
        ->orderBy('lastname')
        ->get();
        
        if ($entities) {
            return response()->json([
                'message' => 'Data is present.',
                'code' => 200,
                'data' => $entities
            ]);
        } else {
            return response()->json([
                'message' => 'Internal server error.',
                'code' => 500
            ]);
        }
    }

    public function getDepartments()
    {
        $campus = session('campus');
        $departments = Department::select(
            'department_code',
            'department'
            )
        ->where('campus', $campus)
        ->orderBy('department')
        ->get();
        
        if ($departments) {
            return response()->json([
                'message' => 'Data is present.',
                'code' => 200,
                'data' => $departments
            ]);
        } else {
            return response()->json([
                'message' => 'Internal server error.',
                'code' => 500
            ]);
        }
    }

    public function getOrganizations()
    {
        $campus = session('campus');
        $organizations = Organization::select(
            'id',
            'organization',
            'type'
            )
        ->where('campus', $campus)
        ->orderBy('organization')
        ->get();
        
        if ($organizations) {
            return response()->json([
                'message' => 'Data is present.',
                'code' => 200,
                'data' => $organizations
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
        $validatedData = $request->validate([
            'user_id' => 'required',
            'firstname' => 'required',
            'lastname' => 'required',
            'entity_type' => 'required',
            'sex' => 'required',
            'department' => 'required',
        ]);

        $userId = $validatedData['user_id'];
        $firstName = $validatedData['firstname'];
        $lastName = $validatedData['lastname'];
        $middleName = $request['middlename'];
        $entityType = $validatedData['entity_type'];
        $sex = $validatedData['sex'];
        $department = $validatedData['department'];
        $campus = session('campus');

        if (CampusEntity::where('user_id', $userId)->exists()) {
            return response()->json([
                'error' => 'User ID already exists in the database.',
                'code' => 400,
            ]);
        }

        $create = CampusEntity::create([
            'user_id' => $userId,
            'firstname' => $firstName,
            'lastname' => $lastName,
            'middlename' => $middleName,
            'type' => $entityType,
            'sex' => $sex,
            'campus' => $campus,
            'department_code' => $department,
        ]);

        if($create) {
            return response()->json([
                'success' => 'A new record has been successfully added.',
                'code' => 200,
            ]);
        } else {
            return response()->json([
                'error' => 'Internal server error.',
                'code' => 500
            ]);
        }
    }

    public function storeDepartment(Request $request)
    {
        $validatedData = $request->validate([
            'department_code' => 'required',
            'department_name' => 'required',
        ]);

        $departmentCode = $validatedData['department_code'];
        $departmentName = $validatedData['department_name'];
        $campus = session('campus');

        if (Department::where('department_code', $departmentCode)->exists()) {
            return response()->json([
                'error' => 'Department code already exists in the database.',
                'code' => 400,
            ]);
        }

        $create = Department::create([
            'department_code' => $departmentCode,
            'department' => $departmentName,
            'campus' => $campus,
        ]);

        if($create) {
            return response()->json([
                'success' => 'A new record has been successfully added.',
                'code' => 200,
            ]);
        } else {
            return response()->json([
                'error' => 'Internal server error.',
                'code' => 500
            ]);
        }
    }

    public function storeOrganization(Request $request)
    {
        $validatedData = $request->validate([
            'organization_name' => 'required',
            'organization_type' => 'required',
        ]);

        $organizationName = $validatedData['organization_name'];
        $organizationType = $validatedData['organization_type'];
        $campus = session('campus');

        $create = Organization::create([
            'organization' => $organizationName,
            'type' => $organizationType,
            'campus' => $campus,
        ]);

        if($create) {
            return response()->json([
                'success' => 'A new record has been successfully added.',
                'code' => 200,
            ]);
        } else {
            return response()->json([
                'error' => 'Internal server error.',
                'code' => 500
            ]);
        }
    }

    public function getSelectedEntity(Request $request)
    {
        $entity = CampusEntity::where('user_id', $request->id)->first();

        if ($entity){
            return response()->json([
                'success' => true, 
                'code' => 200, 
                'data' => $entity
            ]);
        } else {
            return response()->json([
                'error' => 'Internal server error.',
                'code' => 500
            ]);
        }
    }

    public function getSelectedDepartment(Request $request)
    {
        $department = Department::where('department_code', $request->id)->first();

        if ($department){
            return response()->json([
                'success' => true, 
                'code' => 200, 
                'data' => $department
            ]);
        } else {
            return response()->json([
                'error' => 'Internal server error.',
                'code' => 500
            ]);
        }
    }

    public function getSelectedOrganization(Request $request)
    {
        $organization = Organization::where('id', $request->id)->first();

        if ($organization){
            return response()->json([
                'success' => true, 
                'code' => 200, 
                'data' => $organization
            ]);
        } else {
            return response()->json([
                'error' => 'Internal server error.',
                'code' => 500
            ]);
        }
    }

    public function updateEntity(Request $request)
    {
        try {
            $update = CampusEntity::where('user_id', $request->entity_id)->update([
                'firstname' => $request->update_firstname,
                'lastname' => $request->update_lastname,
                'middlename' => $request->update_middlename,
                'type' => $request->update_entity_type,
                'sex' => $request->update_sex,
                'department_code' => $request->update_department
            ]);
        
            if ($update) {
                return response()->json([
                    'success' => true,
                    'code' => 200,
                    'message' => 'Update successful'
                ]);
            } else {
                return response()->json([
                    'error' => 'Internal server error.',
                    'code' => 500,
                    'message' => 'Update failed'
                ]);
            }
        } catch (\Exception $e) {
            return response()->json([
                'error' => $e->getMessage(),
                'code' => $e->getCode(),
                'message' => 'An error occurred during the update'
            ]);
        }
    }

    public function updateDepartment(Request $request)
    {
        try {
            $update = Department::where('department_code', $request->department_code)->update([
                'department' => $request->update_department_name
            ]);
        
            if ($update) {
                return response()->json([
                    'success' => true,
                    'code' => 200,
                    'message' => 'Update successful'
                ]);
            } else {
                return response()->json([
                    'error' => 'Internal server error.',
                    'code' => 500,
                    'message' => 'Update failed'
                ]);
            }
        } catch (\Exception $e) {
            return response()->json([
                'error' => $e->getMessage(),
                'code' => $e->getCode(),
                'message' => 'An error occurred during the update'
            ]);
        }
    }

    public function updateOrganization(Request $request)
    {
        try {
            $update = Organization::where('id', $request->organization_id)->update([
                'organization' => $request->update_organization_name,
                'type' => $request->update_organization_type
            ]);
        
            if ($update) {
                return response()->json([
                    'success' => true,
                    'code' => 200,
                    'message' => 'Update successful'
                ]);
            } else {
                return response()->json([
                    'error' => 'Internal server error.',
                    'code' => 500,
                    'message' => 'Update failed'
                ]);
            }
        } catch (\Exception $e) {
            return response()->json([
                'error' => $e->getMessage(),
                'code' => $e->getCode(),
                'message' => 'An error occurred during the update'
            ]);
        }
    }

    public function destroy(Request $request)
    {
        $entity = CampusEntity::where('user_id', $request->id)->delete();

        if($entity) {
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

    public function destroyDepartment(Request $request)
    {
        $department = Department::where('department_code', $request->id)->delete();

        if($department) {
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

    public function destroyOrganization(Request $request)
    {
        $organization = Organization::where('id', $request->id)->delete();

        if($organization) {
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
