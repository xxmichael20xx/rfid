<?php

namespace Database\Seeders;

use App\Models\Activity;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

class ActivitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create();

        foreach(range(4, 8) as $num) {
            $startDate = Carbon::now()->addDays(rand(1, 60));
            Activity::create([
                'title' => $faker->word() . ' -- ' . $num,
                'description' => $faker->sentence,
                'location' => $faker->word(),
                'start_date' => $startDate->format('Y-m-d'),
                'end_date' => Carbon::parse($startDate)->addDays(rand(1, 10))->format('Y-m-d')
            ]);
        }
    }
}
