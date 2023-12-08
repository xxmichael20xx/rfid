<?php

namespace App\Http\Livewire;

use App\Models\User;
use App\Rules\CurrentPassword;
use App\Rules\NewPassword;
use Illuminate\Validation\Rule;
use Livewire\Component;

class UpdateAccount extends Component
{
    public $user;
    public $form;

    public function updateAccount()
    {
        $userId = $this->user->id;

        // validate the form
        $this->validate([
            'form.email' => ['required', 'email', Rule::unique('users', 'email')->ignore($userId)],
            'form.new_password' => ['nullable', new NewPassword],
            'form.current_password' => ['required', new CurrentPassword],
            'form.contact_phone' => [
                'required',
                'regex:/^[\+]?[(]?[0-9]{3}[)]?[-\s\.]?[0-9]{3}[-\s\.]?[0-9]{4,6}$/',
                Rule::unique('users', 'contact_phone')->ignore($userId)
            ],
            'form.contact_email' => [
                'required',
                'email',
                Rule::unique('users', 'contact_email')->ignore($userId)
            ],
        ], [
            'updateForm.contact_phone' => 'Contact number format is invalid, valid format is: 09123456789'
        ]);

        $user = User::find($this->user->id);
        $user->update([
            'email' => $this->form['email'],
            'contact_phone' => $this->form['contact_phone'],
            'contact_email' => $this->form['contact_email'],
        ]);

        $newPassword = $this->form['new_password'];
        if (! empty($newPassword)) {
            $user->update([
                'password' => bcrypt($newPassword)
            ]);
        }

        // Emit a new event for the notification
        $this->emit('show.dialog', [
            'icon' => 'success',
            'title' => 'Update Success',
            'message' => 'Account has been updated!',
            'reload' => true
        ]);
    }

    public function mount()
    {
        $this->user = auth()->user();

        $this->form = [
            'id' => $this->user->id,
            'email' => $this->user->email,
            'new_password' => '',
            'current_password' => '',
            'contact_email' => $this->user->contact_email,
            'contact_phone' => $this->user->contact_phone,
        ];
    }

    public function render()
    {
        return view('livewire.update-account');
    }
}
