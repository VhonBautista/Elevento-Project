<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Campus;

class CampusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $campuses = [
            ['campus' => 'Alaminos'],
            ['campus' => 'Asingan'],
            ['campus' => 'Bayambang'],
            ['campus' => 'Binmaley'],
            ['campus' => 'Infanta'],
            ['campus' => 'Lingayen'],
            ['campus' => 'SanCarlos'],
            ['campus' => 'StaMaria'],
            ['campus' => 'Urdaneta'],
        ];

        foreach ($campuses as $campusData) {
            Campus::create($campusData);
        }
    }
}
