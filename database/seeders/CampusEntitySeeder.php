<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\CampusEntity;

class CampusEntitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $campusEntities = [
            [
                'user_id' => '23UR0001',
                'firstname' => 'John',
                'lastname' => 'Doe',
                'middlename' => 'M',
                'type' => 'Student',
                'sex' => 'Male',
                'campus' => 'Urdaneta',
                'department_code' => 'BSIT'
            ],
            [
                'user_id' => '23UR0002',
                'firstname' => 'Jane',
                'lastname' => 'Doe',
                'middlename' => 'L',
                'type' => 'Student',
                'sex' => 'Female',
                'campus' => 'Urdaneta',
                'department_code' => 'BSIT'
            ],
            [
                'user_id' => '23UR0003',
                'firstname' => 'Michael',
                'lastname' => 'Smith',
                'middlename' => 'A',
                'type' => 'Employee',
                'sex' => 'Male',
                'campus' => 'Urdaneta',
                'department_code' => 'BSIT'
            ],
            [
                'user_id' => '23UR0004',
                'firstname' => 'Sarah',
                'lastname' => 'Johnson',
                'middlename' => 'C',
                'type' => 'Employee',
                'sex' => 'Female',
                'campus' => 'Urdaneta',
                'department_code' => 'BSIT'
            ],
            [
                'user_id' => '23UR0005',
                'firstname' => 'Robert',
                'lastname' => 'Brown',
                'middlename' => 'D',
                'type' => 'Student',
                'sex' => 'Male',
                'campus' => 'Urdaneta',
                'department_code' => 'BSIT'
            ],
        ];

        foreach ($campusEntities as $campusEntityData) {
            CampusEntity::create($campusEntityData);
        }
    }
}
