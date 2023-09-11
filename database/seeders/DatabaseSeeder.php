<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Database\Seeders\CampusSeeder;
use Database\Seeders\DeparmentSeeder;
use Database\Seeders\CampusEntitySeeder;
use Database\Seeders\OrganizationSeeder;
use Database\Seeders\VenueSeeder;
use Database\Seeders\EventTypeSeeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        DB::beginTransaction();

        try {
            $this->call(CampusSeeder::class);
            $this->call(DepartmentSeeder::class);
            $this->call(CampusEntitySeeder::class);
            $this->call(OrganizationSeeder::class);
            $this->call(VenueSeeder::class);
            $this->call(EventTypeSeeder::class);

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }
}
