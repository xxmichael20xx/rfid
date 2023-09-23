<?php

namespace App\Http\Livewire\BlockManagement;

use App\Models\Block;
use App\Models\Lot;
use Illuminate\Validation\Rule;
use Livewire\Component;

class BlockManagementCreate extends Component
{
    /**
     * Define form variables
     */
    public $newBlock = [
        'block' => '',
        'details' => '',
        'lots' => [
            [
                'lot' => '',
                'details' => ''
            ]
        ]
    ];

    public function rules()
    {
        return [
            'newBlock.block' => 'required',
            'newBlock.details' => 'nullable',
            'newBlock.lots.*.lot' => ['required', 'distinct']
        ];
    }

    public function create()
    {
        // validate the form
        $this->validate();

        // create new block
        $createdBlock = Block::create([
            'block' => $this->newBlock['block'],
            'details' => $this->newBlock['details'],
        ]);

        // check if new block is created
        if ($createdBlock) {
            // add the lots of the block
            foreach ($this->newBlock['lots'] as $newLot) {
                Lot::create([
                    'block_id' => $createdBlock->id,
                    'lot' => $newLot['lot'],
                    'details' => $newLot['details'],
                ]);
            }

            // dispatch a javacript event to trigger the notification
            $this->emit('show.dialog', [
                'icon' => 'success',
                'title' => 'Create Success',
                'message' => 'Block & lots has been successfully created!',
                'redirect' => route('block-management.list')
            ]);
        }
    }

    public function addLot() {
        $this->newBlock['lots'][] = [
            'lot' => '',
            'details' => ''
        ];
    }

    public function removeLot($key)
    {
        unset($this->newBlock['lots'][$key]);
        $this->newBlock['lots'] = array_values($this->newBlock['lots']); // Re-index the array
    }

    public function render()
    {
        return view('livewire.blockmanagement.block-management-create')
            ->extends('layouts.admin')
            ->section('content');
    }
}
