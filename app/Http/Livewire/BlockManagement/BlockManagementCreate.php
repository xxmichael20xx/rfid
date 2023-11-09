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
            'newBlock.block' => [
                'required',
                'numeric',
                'min:1',
                Rule::unique('blocks', 'block')
            ],
            'newBlock.details' => 'nullable',
            'newBlock.lots.*.lot' => [
                'required',
                'numeric',
                'min:1',
                'distinct',
                Rule::unique('lots', 'lot')
            ]
        ];
    }

    public function create()
    {
        // validate the form
        $this->validate($this->rules(), [
            'newBlock.block.unique' => 'The block name is already taken.',
            'newBlock.lots.*.lot.required' => 'The lot name field is required.',
            'newBlock.lots.*.lot.numeric' => 'The lot name should be number.',
            'newBlock.lots.*.lot.min' => 'The lot name should at least 1.',
            'newBlock.lots.*.lot.distinct' => 'The lot name should be unique.',
            'newBlock.lots.*.lot.unique' => 'The lot name already taken.',
        ]);

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
        return view('livewire.BlockManagement.block-management-create')
            ->extends('layouts.admin')
            ->section('content');
    }
}
