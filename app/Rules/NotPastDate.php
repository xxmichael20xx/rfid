<?php

namespace App\Rules;

use Carbon\Carbon;
use Illuminate\Contracts\Validation\Rule;

class NotPastDate implements Rule
{
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
        // Parse the input value as a Carbon date
        $inputDate = Carbon::parse($value);

        // Get the current date
        $currentDate = Carbon::now();

        // Check if the input date is in the past
        return $inputDate->isFuture() || $inputDate->isSameDay($currentDate);
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'The :attribute must be a date in the future or today.';
    }
}
