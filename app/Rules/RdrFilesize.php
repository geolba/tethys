<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\Config;

class RdrFilesize implements Rule
{
    protected $maxFileSize;

    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->maxFileSize = Config::get('enums.max_filesize');
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
        //return Rule::in($this->filetypes);
        return $this->getSize($attribute, $value) <=  $this->maxFileSize;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'file :attribute is too large for the destination storage system.';
    }

     /**
     * Get the size of an attribute.
     *
     * @param  string  $attribute
     * @param  mixed   $value
     * @return mixed
     */
    private function getSize($attribute, $value)
    {
        if (is_numeric($value) && $hasNumeric) {
            return array_get($this->data, $attribute);
        } elseif (is_array($value)) {
            return count($value);
        } elseif ($value instanceof File) {
            return $value->getSize() / 1024;
        }
        return mb_strlen($value);
    }
}
