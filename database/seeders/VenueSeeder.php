<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Venue;

class VenueSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $venues = [
            ['venue_name' => 'Amphitheater', 'image' => null, 'campus' => 'Urdaneta'],
            ['venue_name' => 'Student Activity Center', 'image' => null, 'campus' => 'Urdaneta'],
            ['venue_name' => 'Audio Visual Room', 'image' => null, 'campus' => 'Urdaneta'],
            ['venue_name' => 'Ground Floor Annex', 'image' => null, 'campus' => 'Urdaneta'],
            ['venue_name' => 'Library', 'image' => null, 'campus' => 'Urdaneta'],
            ['venue_name' => 'Covered Court', 'image' => null, 'campus' => 'Urdaneta'],
        ];

        foreach ($venues as $venueData) {
            Venue::create($venueData);
        }
    }
}
