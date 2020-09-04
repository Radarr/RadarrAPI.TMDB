<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;
use Symfony\Component\HttpFoundation\Request;

class MappingAddRequest extends JSONRequest
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
        $arr = [
            'tmdbid' => [
                'required',
                "regex:/^\d+$/",
            ],
              'type' => [
                  'required',
                  Rule::in(['title', 'year']),
              ],
              'language' => [
                  Rule::in(['en', 'fr', 'es', 'de', 'it', 'da', 'nl', 'ja', 'ru', 'pl', 'vi', 'sv', 'no', 'fi', 'tr', 'pt', 'nl', 'el', 'ko', 'hu']),
              ],
        ];

        if ($this->input('type') == 'title') {
              $arr['aka_title'] = ['required', 'regex:/^.{2}.+/'];
        } elseif ($this->input('type') == 'year') {
              $arr['aka_year'] = ['required', "regex:/^(19|20)\d{2}$/"];
        }

        return $arr;
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'tmdbid.required'    => 'A tmdbid for the movie is required',
            'tmdbid.regex'       => 'The format of the tmdbid given is invalid!',
            'type.required'      => 'The type of mapping to add is required',
            'type.in'            => "The type of mapping has to either be 'title' or 'year'.",
            'aka_year.required'  => "The alternative year is required with type 'year'.",
            'aka_year.regex'     => 'The alternative year has to be a valid movie year.',
            'aka_title.required' => "The alternative title is required with type 'title'",
            'aka_title.regex'    => 'The alternative title must be at least 3 letters long',
            'language.in'        => 'The language must be a valid ISO639-1 code.',
        ];
    }
}

class MappingFindRequest extends JSONRequest
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
        $arr = [
            'tmdbid' => [
                'required',
                "regex:/^\d+$/",
            ],
            'type' => [
                'sometimes',
                'required',
                Rule::in(['title', 'year', 'all']),
            ],
            'language' => [
                Rule::in(['en', 'fr', 'es', 'de', 'it', 'da', 'nl', 'ja', 'ru', 'pl', 'vi', 'sv', 'no', 'fi', 'tr', 'pt', 'nl', 'el', 'ko', 'hu']),
            ],
        ];

        return $arr;
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'tmdbid.required' => 'A tmdbid to find mappings by is required.',
            'tmdbid.regex'    => 'The format of the tmdbid given is invalid!',
            'type.required'   => 'The type of mappings to return is required.',
            'type.in'         => "The type of mapping has to be on of 'title', 'year' or 'all'.",
            'language.in'     => 'The language must be a valid ISO639-1 code.',
        ];
    }
}

class MappingGetRequest extends JSONRequest
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
        $arr = [
            'id' => [
                'required',
                "regex:/^\d+$/",
            ],
        ];

        return $arr;
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'id.required' => 'The id of the mapping is required.',
            'id.regex'    => 'The format of the id given is invalid!',
        ];
    }
}
