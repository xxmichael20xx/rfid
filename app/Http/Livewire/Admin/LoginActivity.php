<?php

namespace App\Http\Livewire\Admin;

use App\Models\LoginActivity as ModelsLoginActivity;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class LoginActivity extends Component
{
    public $records;

    public function mount()
    {
        $query = ModelsLoginActivity::with(['user'])->latest();

        if ($search = request()->get('search')) {
            $likeSearch = '%' . $search . '%';
            $query->where(function($query) use ($likeSearch) {
                $query->whereHas('user', function($query) use ($likeSearch) {
                    $query->where(DB::raw("CONCAT(last_name, ', ', first_name, ' ', COALESCE(middle_name, ''))"), 'LIKE', $likeSearch)
                        ->orWhere(DB::raw("CONCAT(last_name, ' ', first_name, ' ', COALESCE(middle_name, ''))"), 'LIKE', $likeSearch)
                        ->orWhere(DB::raw("CONCAT(first_name, ' ', COALESCE(middle_name, ''), ' ', last_name)"), 'LIKE', $likeSearch)
                        ->orWhere(DB::raw("CONCAT(first_name, ' ', last_name)"), 'LIKE', $likeSearch)
                        ->orWhere('first_name', 'LIKE', $likeSearch)
                        ->orWhere('middle_name', 'LIKE', $likeSearch)
                        ->orWhere('last_name', 'LIKE', $likeSearch);
                })
                ->orWhere('browser', 'LIKE', $likeSearch);
            });
        }

        $this->records = $query->get();
    }


    public function render()
    {
        return view('livewire.admin.login-activity')
            ->extends('layouts.admin')
            ->section('content');
    }
}
