<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Department;

class DepartmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $departments = [
            [
                'department_code' => 'ABEL',
                'department' => 'Bachelor of Arts in English Language',
                'campus' => 'Urdaneta'
            ],
            [
                'department_code' => 'BECEd',
                'department' => 'Bachelor of Early Childhood Education',
                'campus' => 'Urdaneta'
            ],
            [
                'department_code' => 'BSEd',
                'department' => 'Bachelor of Secondary Education',
                'campus' => 'Urdaneta'
            ],
            [
                'department_code' => 'BSIT',
                'department' => 'Bachelor of Science in Information Technology',
                'campus' => 'Urdaneta'
            ],
            [
                'department_code' => 'BSMath',
                'department' => 'Bachelor of Science in Mathematics',
                'campus' => 'Urdaneta'
            ],
            [
                'department_code' => 'BSA',
                'department' => 'Bachelor of Science in Architecture',
                'campus' => 'Urdaneta'
            ],
            [
                'department_code' => 'BSCE',
                'department' => 'Bachelor of Science in Civil Engineering',
                'campus' => 'Urdaneta'
            ],
            [
                'department_code' => 'BSCoE',
                'department' => 'Bachelor of Science in Computer Engineering',
                'campus' => 'Urdaneta'
            ],
            [
                'department_code' => 'BSEE',
                'department' => 'Bachelor of Science in Electrical Engineering',
                'campus' => 'Urdaneta'
            ],
            [
                'department_code' => 'BSME',
                'department' => 'Bachelor of Science in Mechanical Engineering',
                'campus' => 'Urdaneta'
            ]
        ];

        foreach ($departments as $departmentData) {
            Department::create($departmentData);
        }
    }
}
