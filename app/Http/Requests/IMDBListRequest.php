<?php

namespace App\Http\Requests;

use Symfony\Component\HttpFoundation\Request;

class IMDBListRequest extends JSONRequest
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
            'listId' => [
                'required',
                "regex:/((ls)|(ur))\d{1,12}/",
            ],
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'listId.required' => 'An id for the imdb list is required!',
            'listId.regex'    => 'The format of the listId given is invalid!',
        ];
    }
}
