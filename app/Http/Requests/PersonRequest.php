<?php
namespace App\Http\Requests;

use App\Http\Requests\Request;

class PersonRequest extends Request
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'academic_title' => 'nullable|min:2|max:255',
            'last_name' => 'required|min:3|max:255',
            'first_name' => 'nullable|min:3|max:255',
            'email' => 'nullable|email|max:100',
            'identifier_orcid' => 'nullable|min:19|max:50',
            'status' => 'required|boolean'
        ];
    }
}
