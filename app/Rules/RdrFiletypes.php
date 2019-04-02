<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
//use Illuminate\Support\Facades\Config;
use App\Models\MimeType;

class RdrFiletypes implements Rule
{
    protected $filetypes;
    // protected $maxFileSize;
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        //$this->mimetypes = Config::get('enums.mimetypes_allowed', ['application/pdf']);
        $this->mimetypes  = MimeType::where('enabled', 1)
        ->pluck('name')
        ->toArray();
        // $this->maxFileSize = Config::get('enums.max_filesize');
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
        $mimeType = $value->getMimeType();//"application/pdf"
        return $value->getPath() != '' &&
        in_array($mimeType, $this->mimetypes);
        // in_array($value->guessExtension(), $this->filetypes); //file extension
        
        
        // &&
        // $this->getSize($attribute, $value) <=  $this->maxFileSize;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'attribute :attribute has not a valid mime type.';
    }
}
