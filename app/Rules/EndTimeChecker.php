<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class EndTimeChecker implements Rule
{
    protected $form;

    protected $message;

    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct(array $form)
    {
        $this->form = $form;

        $this->message = 'The field end time is invalid.';
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
        $startDate = strtotime($this->form['start_date']);
        $endDate = strtotime($this->form['end_date']);

        if ($startDate != $endDate) {
            return true;
        }

        $this->message = 'The field end time should not be before the start time if the start and end date aren\'t the same.';

        // Start date and end date are the same, end_time should not be before start_time
        return strtotime($this->form['end_time']) >= strtotime($this->form['start_time']);
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
