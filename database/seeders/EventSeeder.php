<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Event;

class EventSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $events = [
            [
                'title' => 'Sample Event Workshop',
                'description' => 'Sample Description',
                'cover_photo' => null,
                'start_date' => now()->addDays(22),
                'end_date' => now()->addDays(24),
                'creator_id' => 2,
                'campus' => 'Urdaneta',
                'venue_id' => null,
                'event_type' => 'Workshop',
            ],
            [
                'title' => 'Sample Event Conference 2',
                'description' => 'Sample Description',
                'cover_photo' => null,
                'start_date' => now()->addDays(1),
                'end_date' => now()->addDays(2),
                'creator_id' => 2,
                'campus' => 'Urdaneta',
                'venue_id' => null,
                'event_type' => 'Conference',
            ],
            [
                'title' => 'Sample Event Seminar',
                'description' => 'Sample Description',
                'cover_photo' => null,
                'start_date' => now(),
                'end_date' => now()->addDays(2),
                'creator_id' => 2,
                'campus' => 'Urdaneta',
                'venue_id' => null,
                'event_type' => 'Seminar',
            ],
            [
                'title' => 'Sample Event Conference',
                'description' => 'Sample Description',
                'cover_photo' => null,
                'start_date' => now()->addDays(4),
                'end_date' => now()->addDays(5),
                'creator_id' => 2,
                'campus' => 'Urdaneta',
                'venue_id' => null,
                'event_type' => 'Conference',
            ],
            [
                'title' => 'Sample Event Festive',
                'description' => 'Sample Description',
                'cover_photo' => null,
                'start_date' => now()->addDays(12),
                'end_date' => now()->addDays(12),
                'creator_id' => 2,
                'campus' => 'Urdaneta',
                'venue_id' => null,
                'event_type' => 'Festive',
            ]
        ];

        foreach ($events as $eventData) {
            Event::create($eventData);
        }
    }
}
