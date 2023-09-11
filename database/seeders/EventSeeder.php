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
            ['event_type' => 'Conference'],
            ['event_type' => 'Workshop'],
            ['event_type' => 'Seminar'],
            ['event_type' => 'Webinar'],
            ['event_type' => 'Training'],
            ['event_type' => 'Exhibition'],
            ['event_type' => 'Festive'],
        ];

        foreach ($events as $eventData) {
            Event::create($eventData);
        }
    }
}
