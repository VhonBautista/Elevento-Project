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
            ['venue_name' => 'Amphitheater', 'handler_name' => 'Corazon Alabamba', 'capacity' => 300, 'image' => null, 'campus' => 'Urdaneta'],
            ['venue_name' => 'Student Activity Center', 'handler_name' => 'Corazon Alabamba', 'capacity' => 300, 'image' => null, 'campus' => 'Urdaneta'],
            ['venue_name' => 'Audio Visual Room', 'handler_name' => 'Corazon Alabamba', 'capacity' => 300, 'image' => null, 'campus' => 'Urdaneta'],
            ['venue_name' => 'Ground Floor Annex', 'handler_name' => 'Corazon Alabamba', 'capacity' => 300, 'image' => null, 'campus' => 'Urdaneta'],
            ['venue_name' => 'Library', 'handler_name' => 'Corazon Alabamba', 'capacity' => 300, 'image' => null, 'campus' => 'Urdaneta'],
            ['venue_name' => 'Covered Court', 'handler_name' => 'Corazon Alabamba', 'capacity' => 300, 'image' => null, 'campus' => 'Urdaneta'],
        ];

        foreach ($venues as $venueData) {
            Venue::create($venueData);
        }
    }
}
