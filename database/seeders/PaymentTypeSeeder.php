<?php

namespace Database\Seeders;

use App\Models\PaymentType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PaymentTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // The initial types of payments
        $types = [
            [
                'type' => 'Guard Security',
                'amount' => 1250,
                'frequency' => 'monthly',
                'is_recurring' => true,
                'recurring_day' => 5
            ],
            [
                'type' => 'Chrismas Party',
                'amount' => 3000,
                'frequency' => 'annually',
                'is_recurring' => true,
                'recurring_day' => 5
            ],
            [
                'type' => 'Maintenance',
                'amount' => 1500,
                'frequency' => 'monthly',
                'is_recurring' => true,
                'recurring_day' => 5
            ]
        ];

        // Iterate all types
        foreach ($types as $data) {
            // Check if the Payment Type doesn't exist
            if (! PaymentType::where('type', data_get($data, 'type'))->first()) {
                // Add new Payment type
                PaymentType::create($data);
            }
        }
    }
}
