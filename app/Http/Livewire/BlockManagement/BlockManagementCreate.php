<?php

namespace App\Http\Livewire\BlockManagement;

use App\Models\Block;
use App\Models\Lot;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Livewire\Component;
use Livewire\WithFileUploads;

class BlockManagementCreate extends Component
{
    use WithFileUploads;

    /**
     * Define form variables
     */
    public $newBlock = [
        'block' => '',
        'details' => '',
        'lots' => [
            [
                'lot' => '',
                'details' => '',
                'image' => '',
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
                'distinct'
            ],
            'newBlock.lots.*.image' => ['nullable', 'image']
        ];
    }

    public function updatedNewBlockLots($value, $index)
    {
        dd($value, $index);
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
                $imageUrl = null;

                if ($image = $newLot['image']) {
                    $imageUrl = Storage::putFileAs('images/lots', $image, $image->hashName());
                }

                Lot::create([
                    'block_id' => $createdBlock->id,
                    'lot' => $newLot['lot'],
                    'details' => $newLot['details'],
                    'image' => $imageUrl
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
            'details' => '',
            'image' => ''
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
