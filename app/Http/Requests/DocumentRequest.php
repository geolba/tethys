<?php
namespace App\Http\Requests;

use App\Http\Requests\Request;

class DocumentRequest extends Request
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
            'type' => 'required|min:5',
            'server_state' => 'required',
            // 'author' => 'required|min:4',
            // 'stock' => 'required|integer',
            // 'year' => 'required|integer|min:4'
        ];
    }
}
