<?php

namespace Database\Seeders;

use App\Models\Block;
use App\Models\Lot;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

class BlockLotSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $letterBlocks = $this->getRandomUniqueLetters();
        $numberBlocks = range(1, 4);
        $blocks = array_merge($letterBlocks, $numberBlocks);
        $addedBlocks = 0;

        foreach ($blocks as $block) {
            // check if block exists
            if (! Block::where('block', $block)->first()) {
                // add the blocks
                $newBlock = Block::create([
                    'block' => 'Block '.$block
                ]);
                if ($newBlock) {
                    // generate lots betwen 1 and 18
                    $lots = range(1, 4);
                    foreach ($lots as $lot) {
                        Lot::create([
                            'block_id' => $newBlock->id,
                            'lot' => 'Lot '.$block.'-'.$lot
                        ]);
                    }

                    $addedBlocks++;
                }
            }
        }

        $this->command->info('Added a total of '.$addedBlocks.' blocks.');
    }

    /**
     * Get a random 10 letters ranging from 'A' to 'Z'
     */
    private function getRandomUniqueLetters(int $count = 10)
    {
        // used php 'range' to create a list
        // of alphabet letters
        $alphabet = range('A', 'Z');

        // shiffle the list of alphabet letters
        shuffle($alphabet);

        // take only 10 letters
        return array_slice($alphabet, 0, $count);
    }
}
