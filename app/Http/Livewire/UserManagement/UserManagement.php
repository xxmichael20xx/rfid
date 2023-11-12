<?php

namespace App\Http\Livewire\UserManagement;

use App\Models\User;
use Illuminate\Validation\Rule;
use Livewire\Component;

class UserManagement extends Component
{
    protected $listeners = [
        'updateRole',
        'deleteUser'
    ];

    public $users;
    public $search;

    public $createForm = [
        'first_name',
        'last_name',
        'middle_name',
        'email',
        'password',
        'role' => '',
        'contact_phone',
        'contact_email'
    ];

    public $updateForm;
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
            'createForm.role' => ['required'],
            'createForm.contact_phone' => [
                'required',
                'regex:/^09\d{9}$/',
                Rule::unique('users', 'contact_phone')
            ],
            'createForm.contact_email' => ['required', 'email', Rule::unique('users', 'contact_email')],
        ], [
            'createForm.contact_phone' => 'Contact number format is invalid, valid format is: 09123456789'
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

    public function deleteUser($id)
    {
        // fetch the user
        $user = User::find($id);

        // delete the user
        $user->delete();

        // emit a new event for the notification
        $this->emit('show.dialog', [
            'icon' => 'success',
            'title' => 'Delete Success',
            'message' => 'User has been successfully deleted!',
            'reload' => true
        ]);

    }

    public function prepareUpdate($id)
    {
        $user = User::find($id);
        $this->updateForm = $user->toArray();

        $this->emit('show.prepared-user');
    }

    public function update()
    {
        $userId = data_get($this->updateForm, 'id');
        $nameRules = ['required', 'string', 'min:2', 'max:30'];

        $this->validate([
            'updateForm.first_name' => $nameRules,
            'updateForm.last_name' => $nameRules,
            'updateForm.middle_name' => ['nullable', 'string', 'min:2', 'max:30'],
            'updateForm.role' => ['required'],
            'updateForm.contact_phone' => [
                'required',
                'regex:/^09\d{9}$/',
                Rule::unique('users', 'contact_phone')->ignore(data_get($this->updateForm, 'id'))
            ],
            'updateForm.contact_email' => [
                'required',
                'email',
                Rule::unique('users', 'contact_email')->ignore(data_get($this->updateForm, 'id'))
            ],
        ], [
            'updateForm.contact_phone' => 'Contact number format is invalid, valid format is: 09123456789'
        ]);

        $user = User::find($userId);
        $user->update($this->updateForm);

        // emit a new event for the notification
        $this->emit('show.dialog', [
            'icon' => 'success',
            'title' => 'Update Success',
            'message' => 'User has been updated!',
            'reload' => true
        ]);
    }

    public function mount()
    {
        $userId = auth()->user()->id;
        $this->users = User::where('id', '<>', $userId)
            ->where('Role', '<>', 'User')
            ->get();

        if ($search = request()->get('search')) {
            $this->search = $search;

            $likeSearch = '%'.$search.'%';
            $this->users = User::where('id', '<>', $userId)
                ->where('first_name', 'LIKE', $likeSearch)
                ->orWhere('last_name', 'LIKE', $likeSearch)
                ->orWhere('middle_name', 'LIKE', $likeSearch)
                ->get();
        }
    }

    public function render()
    {
        return view('livewire.UserManagement.user-management')
        ->extends('layouts.admin')
        ->section('content');
    }
}
