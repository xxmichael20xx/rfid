<?php

namespace Database\Seeders;

use App\Models\Block;
use App\Models\HomeOwner;
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
    public function run()
    {
        $faker = Faker::create();

        foreach (range(1, 10) as $num) {
            $myBlock = Block::inRandomOrder()->first();
            $myLot = Lot::where('block_id', $myBlock->id)->inRandomOrder()->first();

            // create home owner
            $newHomeOwner = new HomeOwner;
            $newHomeOwner->first_name = $faker->firstName();
            $newHomeOwner->last_name = $faker->lastName();
            $newHomeOwner->middle_name = $faker->lastName();
            $newHomeOwner->block = $myBlock->id;
            $newHomeOwner->lot = $myLot->id;
            $newHomeOwner->contact_no = '09'.rand(111111111, 999999999);
            $newHomeOwner->save();

            $myLot->update([
                'availability' => 'unavailable'
            ]);

            /**
             * mt_rand(1, 99999999) generates a random number between 1 and 99,999,999 (inclusive).
             * str_pad is used to ensure that the generated number always has 8 digits.
             * If the random number is less than 8 digits, it adds leading zeros.
             */
            $rfid =  str_pad(mt_rand(1, 99999999), 8, '0', STR_PAD_LEFT);
            Rfid::create([
                'home_owner_id' => $newHomeOwner->id,
                'rfid' => $rfid
            ]);
        }
    }
}
