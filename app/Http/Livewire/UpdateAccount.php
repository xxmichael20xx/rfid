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
        // validate the form
        $this->validate([
            'form.email' => ['required', 'email', Rule::unique('users', 'email')->ignore($this->user->id, 'id')],
            'form.new_password' => ['nullable', new NewPassword],
            'form.current_password' => ['required', new CurrentPassword]
        ]);

        $user = User::find($this->user->id);
        $user->update([
            'email' => $this->form['email']
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
        ];
    }

    public function render()
    {
        return view('livewire.update-account');
    }
}
