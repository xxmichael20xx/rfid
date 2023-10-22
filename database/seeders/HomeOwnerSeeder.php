<?php

namespace Database\Seeders;

use App\Models\Block;
use App\Models\HomeOwner;
use App\Models\HomeOwnerBlockLot;
use App\Models\HomeOwnerVehicle;
use App\Models\Lot;
use App\Models\Rfid;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

class HomeOwnerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        $faker = Faker::create();
        $cars = config('cars');

        foreach (range(1, 10) as $num) {
            // create random birthday
            $startDate = "-99 years";
            $endDate = "-0 years";
            $randomDateOfBirth = $faker->dateTimeBetween($startDate, $endDate)->format('Y-m-d');

            // get random gender
            $gender = $faker->randomElement(['male', 'female']);
            $profile = 'images/default_male.jpg';

            if ($gender == 'female') {
                $profile = 'images/default_female.jpg';
            }

            // create home owner
            $newHomeOwner = new HomeOwner;
            $newHomeOwner->first_name = $faker->firstName();
            $newHomeOwner->last_name = $faker->lastName();
            $newHomeOwner->middle_name = $faker->lastName();
            $newHomeOwner->date_of_birth = $randomDateOfBirth;
            $newHomeOwner->email = $faker->safeEmail();
            $newHomeOwner->contact_no = '09'.rand(111111111, 999999999);
            $newHomeOwner->gender = $gender;
            $newHomeOwner->profile = $profile;
            $newHomeOwner->save();

            // Add Block & Lots
            foreach (range(1, 3) as $num) {
                $lot = Lot::where('availability', 'available')->inRandomOrder()->first();
                $block = $lot->block_id;

                HomeOwnerBlockLot::create([
                    'home_owner_id' => $newHomeOwner->id,
                    'block' => $block,
                    'lot' => $lot->id
                ]);

                Lot::find($lot->id)->update([
                    'availability' => 'unavailable'
                ]);

                // Add Vehicles
                $randomPrefix = $faker->regexify('[A-Z]{3}'); // Generates a random 3-letter prefix
                $licensePlate = $randomPrefix . '-'. $faker->numberBetween(1000, 9999);
                $carName = $cars[array_rand($cars)];

                $homeOwnerHevicle = HomeOwnerVehicle::create([
                    'home_owner_id' => $newHomeOwner->id,
                    'plate_number' => $licensePlate,
                    'car_type' => $carName
                ]);

                // Add RFID to Vehicle
                $rfid =  str_pad(mt_rand(1, 99999999), 8, '0', STR_PAD_LEFT);
                Rfid::create([
                    'vehicle_id' => $homeOwnerHevicle->id,
                    'rfid' => $rfid
                ]);
            }
        }
    }
}
