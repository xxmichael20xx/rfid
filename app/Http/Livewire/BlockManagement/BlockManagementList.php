<?php

namespace App\Http\Livewire\BlockManagement;

use App\Models\Block;
use App\Models\Lot;
use Livewire\Component;

class BlockManagementList extends Component
{
    /**
     * Define list variables
     */
    public $blocks;
    public $activeBlock;

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

    public function mount()
    {
        // set the list of blocks from database
        $this->blocks = Block::with(['lots'])->get();
    }

    public function render()
    {
        return view('livewire.blockmanagement.block-management-list')
            ->extends('layouts.admin')
            ->section('content');
    }
}
