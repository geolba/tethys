<?php

namespace App\Http\Requests\Collection;

use App\Http\Requests\Request;

/**
 * Class UpdatePageRequest.
 */
class CollectionRequest extends Request
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;//return access()->allow('edit-page');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name'       => 'required|max:255',
            'role_id' => 'required',
        ];
    }
}
