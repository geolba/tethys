<?php

namespace App\Http\Requests\Pages;

use App\Http\Requests\Request;

/**
 * Class EditPageRequest.
 */
class EditPageRequest extends Request
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        true;//return access()->allow('edit-page');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            //
        ];
    }
}
