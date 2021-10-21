<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class ValidDuration implements Rule
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
        $valid = true;

        foreach(explode(' - ', $value) as $date) {
            if (!strtotime($date))
                $valid = false;
        }

        return $valid;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'The duration is not valid.';
    }
}
