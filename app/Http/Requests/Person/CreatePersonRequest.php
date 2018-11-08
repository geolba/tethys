<?php
namespace App\Http\Requests\Person;

use App\Http\Requests\Request;
use Illuminate\Validation\Rule;

class CreatePersonRequest extends Request
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
            'last_name' => 'required|min:3|max:255|unique_with:persons,first_name,date_of_birth',
            'first_name' => 'required|min:3|max:255',
            'email' => 'required|email|max:50|unique:persons,email',
            // 'email' => [
            //     'required', 'email', 'max:100',
            //     Rule::unique('persons')->ignore($user->id),
            // ],
            'identifier_orcid' => 'nullable|min:19|max:50',
            'status' => 'required|boolean',
            'date_of_birth' => 'required|date'
        ];
    }
}
