<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Organization;

class OrganizationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $organizations = [
            [
                'organization' => "Information Technologists' League", 
                'type' => 'Student Organization', 
                'campus' => 'Urdaneta'
            ],
            [
                'organization' => 'Student Supreme Council', 
                'type' => 'Student Organization', 
                'campus' => 'Urdaneta'
            ],
            [
                'organization' => 'The Technoscope Publications', 
                'type' => 'Student Organization', 
                'campus' => 'Urdaneta'
            ],
            [
                'organization' => 'Institute of Integrated Electrical Engineers-PSU Student Chapter', 
                'type' => 'Student Organization', 
                'campus' => 'Urdaneta'
            ],
            [
                'organization' => 'Philippine Institute of Civil Engineers – PSU Student Chapter', 
                'type' => 'Student Organization', 
                'campus' => 'Urdaneta'
            ],
            [
                'organization' => 'Institute of Computer Engineers of the Philippines Student Edition', 
                'type' => 'Student Organization', 
                'campus' => 'Urdaneta'
            ],
            [
                'organization' => 'Association of Civil Engineering Students', 
                'type' => 'Student Organization', 
                'campus' => 'Urdaneta'
            ],
            [
                'organization' => 'Philippine Society of Mechanical Engineers PSU Student Unit', 
                'type' => 'Student Organization', 
                'campus' => 'Urdaneta'
            ],
            [
                'organization' => 'League of English Major Students', 
                'type' => 'Student Organization', 
                'campus' => 'Urdaneta'
            ],
            [
                'organization' => 'United Architects of the Philippines Student Auxiliary-PSU.Urd', 
                'type' => 'Student Organization', 
                'campus' => 'Urdaneta'
            ],
            [
                'organization' => 'Kapisanan ng mga Nagpapakadalubhasa sa Filipino', 
                'type' => 'Student Organization', 
                'campus' => 'Urdaneta'
            ],
            [
                'organization' => 'Society of Holistic & Innovative Educators Leading With Discipline', 
                'type' => 'Student Organization', 
                'campus' => 'Urdaneta'
            ],
            [
                'organization' => 'Guild of Young Mathematicians and Statisticians', 
                'type' => 'Student Organization', 
                'campus' => 'Urdaneta'
            ],
            [
                'organization' => 'Creative Young Minds Educator’s League', 
                'type' => 'Student Organization', 
                'campus' => 'Urdaneta'
            ],
            [
                'organization' => 'Guild of Multimedia Artists', 
                'type' => 'Student Organization', 
                'campus' => 'Urdaneta'
            ],
            [
                'organization' => 'Rockpoint', 
                'type' => 'Student Organization', 
                'campus' => 'Urdaneta'
            ],
            [
                'organization' => 'Sports, Physical Education and Recreation', 
                'type' => 'Student Organization', 
                'campus' => 'Urdaneta'
            ],
            [
                'organization' => 'Peers Facilitator Circle', 
                'type' => 'Student Organization', 
                'campus' => 'Urdaneta'
            ],
            [
                'organization' => 'Urdaneta Performing Arts Guild', 
                'type' => 'Student Organization', 
                'campus' => 'Urdaneta'
            ],
        ];

        foreach ($organizations as $organizationData) {
            Organization::create($organizationData);
        }
    }
}
