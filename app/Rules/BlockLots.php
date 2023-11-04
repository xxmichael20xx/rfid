<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class BlockLots implements Rule
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
        if (empty($value)) {
            $this->message = 'The field block and lots is required';
            return false;
        }

        if (! is_array($value)) {
            $this->message = 'Invalid selected options.';
            return false;
        }

        if (count($value) < 1) {
            $this->message = 'Select at least one (1) lot.';
            return false;
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
        return $this->message;
    }
}
