<?php

namespace Database\Seeders;

use App\Models\Activity;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class ActivitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Activity::insert([
            [
                'title' => 'Campus Clean-Up',
                'description' => 'Join us in making the campus cleaner and greener!',
                'start_date' => Carbon::now()->addDays(2),
                'end_date' => Carbon::now()->addDays(2)->addHours(2),
                'location' => 'Block A - Main Hall',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title' => 'Career Talk by TechCorp',
                'description' => 'Industry experts share job-hunting tips and career guidance.',
                'start_date' => Carbon::now()->addDays(5),
                'end_date' => Carbon::now()->addDays(5)->addHours(3),
                'location' => 'Auditorium',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title' => 'Coding Marathon',
                'description' => '48-hour coding challenge open to all students.',
                'start_date' => Carbon::now()->addWeek(),
                'end_date' => Carbon::now()->addWeek()->addHours(48),
                'location' => 'Lab 3, IT Building',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
