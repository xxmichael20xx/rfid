<?php

namespace App\Http\Livewire\UserManagement;

use App\Models\User;
use Illuminate\Validation\Rule;
use Livewire\Component;

class UserManagement extends Component
{
    protected $listeners = ['updateRole'];

    public $users;
    public $search;

    public $createForm = [
        'first_name',
        'last_name',
        'middle_name',
        'email',
        'password',
        'role' => ''
    ];
    public $roles = ['Admin', 'Guard', 'Treasurer'];

    public function create()
    {
        $nameRules = ['required', 'string', 'min:2', 'max:30'];

        $this->validate([
            'createForm.first_name' => $nameRules,
            'createForm.last_name' => $nameRules,
            'createForm.middle_name' => ['nullable', 'string', 'min:2', 'max:30'],
            'createForm.email' => ['required', 'email', Rule::unique('users', 'email')],
            'createForm.password' => ['required', 'string', 'min:8'],
            'createForm.role' => ['required']
        ]);

        // create new user
        $rawPassword = $this->createForm['password'];
        data_set($this->createForm, 'password', bcrypt($rawPassword));
        User::create($this->createForm);

        // emit a new event for the notification
        $this->emit('show.dialog', [
            'icon' => 'success',
            'title' => 'Add Success',
            'message' => 'New user has been successfully added!',
            'reload' => true
        ]);
    }

    public function updateRole($data)
    {
        $id = data_get($data, 'id');
        $role = data_get($data, 'role');

        $user = User::find($id);
        $user->update(compact('role'));

        // emit a new event for the notification
        $this->emit('show.dialog', [
            'icon' => 'success',
            'title' => 'Update Success',
            'message' => 'User role has been updated!',
            'reload' => true
        ]);
    }

    public function mount()
    {
        $userId = auth()->user()->id;
        $this->users = User::where('id', '<>', $userId)->get();

        if ($search = request()->get('search')) {
            $this->search = $search;
        }
    }

    public function render()
    {
        return view('livewire.usermanagement.user-management')
        ->extends('layouts.admin')
        ->section('content');
    }
}
