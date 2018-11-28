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
    public function __construct($fileIndex)
    {
        $this->maxFileSize = Config::get('enums.max_filesize');
        $this->fileIndex = $fileIndex;
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
        // $upload_max_size = ini_get('upload_max_filesize');
        $fileSize = filesize($value);
        return $fileSize <= $this->maxFileSize * 1024;
        // return $this->getSize($attribute, $value) <= $this->maxFileSize;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'file number '. $this->fileIndex .' is too large for the destination storage system.';
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
