<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\Hash;

class NewPassword implements Rule
{
    protected $message;

    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        if (! empty($value)) {
            $hashPassword = auth()->user()->password;

            if (strlen($value) < 8) {
                $this->message = 'New password must be at least 8 characters.';
                return false;
            }

            if (Hash::check($value, $hashPassword)) {
                $this->message = 'New password should not be the same as the current password';
                return false;
            }
        }

        return true;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return $this->message;;
    }
}
