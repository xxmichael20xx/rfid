<?php

namespace App\Http\Livewire\Visitor;

use App\Models\Visitor;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class VisitorMonitoring extends Component
{
    public $visitors;

    public function mount()
    {
        $visitorQuery = Visitor::with('for')->whereNotNull('time_in');

        if ($search = request('search')) {
            $likeSearch = '%' . $search . '%';
            $visitorQuery = $visitorQuery->where(function ($query) use ($likeSearch) {
                $query->where(function ($query) use ($likeSearch) {
                    $query->where(DB::raw("CONCAT(last_name, ', ', first_name)"), 'LIKE', $likeSearch)
                        ->orWhere(DB::raw("CONCAT(first_name, ' ', last_name)"), 'LIKE', $likeSearch);
                })
                ->orWhereHas('for', function ($query) use ($likeSearch) {
                    $query->where(function ($query) use ($likeSearch) {
                        $query->where(DB::raw("CONCAT(last_name, ', ', first_name, ' ', COALESCE(middle_name, ''))"), 'LIKE', $likeSearch)
                            ->orWhere(DB::raw("CONCAT(last_name, ' ', first_name, ' ', COALESCE(middle_name, ''))"), 'LIKE', $likeSearch)
                            ->orWhere(DB::raw("CONCAT(first_name, ' ', COALESCE(middle_name, ''), ' ', last_name)"), 'LIKE', $likeSearch)
                            ->orWhere(DB::raw("CONCAT(first_name, ' ', last_name)"), 'LIKE', $likeSearch)
                            ->orWhere('first_name', 'LIKE', $likeSearch)
                            ->orWhere('middle_name', 'LIKE', $likeSearch)
                            ->orWhere('last_name', 'LIKE', $likeSearch);
                    });
                });
            });
        }

        $this->visitors = $visitorQuery
            ->orderByDesc('time_in')
            ->get();
    }

    public function render()
    {
        return view('livewire.Visitor.visitor-monitoring')
            ->extends('layouts.admin')
            ->section('content');
    }
}
