<?php
namespace App\Http\Requests;

use App\Http\Requests\Request;

class ProjectRequest extends Request
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
            
            'name' => [
                'required',
                'min:3',
                'max:255',
            ],
            'label' => [
                'required',
                'min:3',
                'max:20',
            ],
            'description' => [
                'required',
            ],
        ];
    }
}
