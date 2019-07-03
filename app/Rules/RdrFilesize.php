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
        $this->maxFileSize = $this->maximumUploadSize();
        //ini_get('upload_max_filesize');// Config::get('enums.max_filesize');//10240
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
        return $fileSize <= $this->maxFileSize;
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
     * The maximum file upload size by getting PHP settings
     * @return integer|float file size limit in BYTES based
     */
    private function maximumUploadSize()
    {
        static $upload_size = null;
        if ($upload_size === null) {
            $post_max_size = $this->returnBytes('post_max_size');
            $upload_max_filesize = $this->returnBytes('upload_max_filesize');
            $memory_limit = $this->returnBytes('memory_limit');
            // Even though we disable all of variables in php.ini. These still use default value
            // Nearly impossible but check for sure
            if (empty($post_max_size) && empty($upload_max_filesize) && empty($memory_limit)) {
                return false;
            }
            $upload_size = min($post_max_size, $upload_max_filesize, $memory_limit);
        }
        return $upload_size;
    }

    private function returnBytes($val)
    {
        $value = ini_get($val);

        // Value must be a string.
        if (!is_string($value)) {
            return false;
        }
        preg_match('/^(?<value>\d+)(?<option>[K|M|G]*)$/i', $value, $matches);
        $value = (int) $matches['value'];
        $option = strtoupper($matches['option']);

        if ($option) {
            if ($option === 'K') {
                $value *= 1024;
            } elseif ($option === 'M') {
                $value *= 1024 * 1024;
            } elseif ($option === 'G') {
                $value *= 1024 * 1024 * 1024;
            }
        }
        return $value;
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
