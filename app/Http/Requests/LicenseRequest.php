<?php
namespace App\Http\Requests;

use App\Http\Requests\Request;

class LicenseRequest extends Request
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
            'desc_text' => [
                'max:4000',
            ],
            'language' => [
                'max:3',
            ],
            'link_licence' => [
                'required',
                'url:max:255',
            ],
            'link_logo' => [
                'url',
                'max:255',
            ],
            'mime_type' => [
                'max:30',
            ],
            'name_long' => [
                'required',
                'min:5',
                'max:255',
            ],
            'sort_order' => [
                'required',
                'integer',
            ],
            'active' => [
                'required',
                'boolean',
            ],
            'pod_allowed' => [
                'required',
                'boolean',
            ],
        ];
    }
}
