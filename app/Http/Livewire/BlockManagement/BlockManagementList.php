<?php

namespace App\Http\Livewire\BlockManagement;

use App\Models\Block;
use App\Models\Lot;
use Illuminate\Support\Arr;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Unique;
use Livewire\Component;

class BlockManagementList extends Component
{
    /**
     * Define list variables
     */
    public $blocks;
    public $activeBlock;

    public $lotForm = [
        'block' => '',
        'lots' => [
            [
                'lot' => '',
                'details' => ''
            ]
        ]
    ];

    public $editLotForm = [
        'id' => '',
        'lot' => '',
        'details' => ''
    ];

    public $editBlockForm = [
        'id' => '',
        'block' => ''
    ];

    /**
     * Prepare the block lot form via block id
     */
    public function prepareBlock($id)
    {
        data_set($this->lotForm, 'block', $id);

        $this->emit('block.prepared');
    }

    public function addLot() {
        $this->lotForm['lots'][] = [
            'lot' => '',
            'details' => ''
        ];
    }

    public function removeLot($key)
    {
        unset($this->lotForm['lots'][$key]);
        $this->lotForm['lots'] = array_values($this->lotForm['lots']); // Re-index the array
    }

    public function cancelCreate()
    {
        $this->lotForm = [
            'block' => '',
            'lots' => [
                [
                    'lot' => '',
                    'details' => ''
                ]
            ]
        ];
    }

    public function createLots()
    {
        $blockId = data_get($this->lotForm, 'block');
    
        $this->validate([
            'lotForm.lots.*.lot' => [
                'required',
                'numeric',
                'min:1',
                'distinct',
                Rule::unique('lots')->where(function($query) use ($blockId) {
                    return $query->where('block_id', $blockId);
                })
            ]
        ], [
            'lotForm.lots.*.lot.required' => 'The lot name field is required.',
            'lotForm.lots.*.lot.numeric' => 'The lot name should be number.',
            'lotForm.lots.*.lot.min' => 'The lot name should at least 1.',
            'lotForm.lots.*.lot.distinct' => 'The lot name should be unique.',
            'lotForm.lots.*.lot.unique' => 'The lot name already taken.'
        ]);

        $block = Block::find($blockId);

        if (! $block) {
            $this->emit('create.failed.no-data');
            return false;
        }

        // add the lots of the block
        foreach ($this->lotForm['lots'] as $newLot) {
            Lot::create([
                'block_id' => $block->id,
                'lot' => $newLot['lot'],
                'details' => $newLot['details'],
            ]);
        }

        // dispatch a javacript event to trigger the notification
        $this->emit('show.dialog', [
            'icon' => 'success',
            'title' => 'Create Success',
            'message' => 'New lots has been successfully created!',
            'reload' => true
        ]);
    }

    public function prepareEditLot($id)
    {
        $lot = Lot::find($id);
        $this->editLotForm = [
            'id' => $id,
            'lot' => $lot->lot,
            'details' => $lot->details
        ];

        $this->emit('lot.prepared');
    }

    public function updateLot()
    {
        $lotId = data_get($this->editLotForm, 'id');
        $lot = Lot::find($lotId);
        $blockId = $lot->block_id;

        $this->validate([
            'editLotForm.id' => ['required', Rule::exists('lots', 'id')],
            'editLotForm.lot' => [
                'required',
                'numeric',
                'min:1',
                Rule::unique('lots', 'lot')->where(function($query) use ($blockId) {
                    return $query->where('block_id', $blockId);
                })->ignore($lotId)
            ],
            'editLotForm.details' => ['nullable'],
        ]);

        $lot->update([
            'lot' => data_get($this->editLotForm, 'lot'),
            'details' => data_get($this->editLotForm, 'details'),
        ]);

        // dispatch a javacript event to trigger the notification
        $this->emit('show.dialog', [
            'icon' => 'success',
            'title' => 'Update Success',
            'message' => 'Lots has been successfully updated!',
            'reload' => true
        ]);
    }

    public function cancelEditLot()
    {
        $this->editLotForm = [
            'id' => '',
            'lot' => ''
        ];
    }

    public function cancelEditBlock()
    {
        $this->editBlockForm = [
            'id' => '',
            'block' => ''
        ];
    }

    public function setActiveBlock($id)
    {
        if ($block = Block::with('lots')->find($id)) {
            // set the active block for the modal
            $this->activeBlock = $block;

            // emit an event to display the modal
            $this->emit('block-management.show-list');
        } else {
            // emit an event to display no data dialog
            $this->emit('block-management.no-data');
        }
    }

    public function deleteLot($id) {
        $lot = Lot::find($id);

        if (! $lot) {
            // emit a new event for the notification
            $this->emit('show.dialog', [
                'icon' => 'warning',
                'title' => 'Data not found',
                'message' => 'Lot data not found!',
            ]);
        } else {
            $lot->delete();

            // emit a new event for the notification
            $this->emit('show.dialog', [
                'icon' => 'success',
                'title' => 'Delete success',
                'message' => 'Lot has been deleted!',
                'reload' => true
            ]);
        }
    }

    public function prepareBlockEdit($id)
    {
        $editBlock = Block::find($id);

        $this->editBlockForm = [
            'id' => $id,
            'block' => $editBlock->block
        ];

        $this->emit('block.update-prepared');
    }

    public function updateBlock()
    {
        $blockId = $this->editBlockForm['id'];

        // validate the block form
        $this->validate([
            'editBlockForm.block' => [
                'required',
                Rule::unique('blocks', 'block')->ignore($blockId)
            ]
        ]);

        $editBlock = Block::find($blockId);
        $editBlock->update(Arr::only($this->editBlockForm, 'block'));

        // emit a new event for the notification
        $this->emit('show.dialog', [
            'icon' => 'success',
            'title' => 'Update success',
            'message' => 'Block has been updated!',
            'reload' => true
        ]);
    }

    public function mount()
    {
        // set the list of blocks from database
        $this->blocks = Block::with(['lots'])->get();
    }

    public function render()
    {
        return view('livewire.BlockManagement.block-management-list')
            ->extends('layouts.admin')
            ->section('content');
    }
}
