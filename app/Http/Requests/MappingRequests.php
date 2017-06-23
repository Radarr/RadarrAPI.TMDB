<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Illuminate\Validation\Rule;

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
          'tmdbid' => array(
            "required",
            "regex:/^\d+$/"
          ),
            'type' => array(
                "required",
                Rule::in(['title', 'year']),
            )
        ];

        if ($this->input("type") == "title")
        {
            $arr["aka_title"] = ["required", "regex:/^.{2}.+/"];
        }
        else if ($this->input("type") == "year")
        {
            $arr["aka_year"] = ["required", "regex:/^(19|20)\d{2}$/"];
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
            'tmdbid.required' => 'A tmdbid for the movie is required',
            'tmdbid.regex'  => 'The format of the tmdbid given is invalid!',
            "type.required" => "The type of mapping to add is required",
            "type.in" => "The type of mapping has to either be 'title' or 'year'.",
            "aka_year.required" => "The alternative year is required with type 'year'.",
            "aka_year.regex" => "The alternative year has to be a valid movie year.",
            "aka_title.required" => "The alternative title is required with type 'title'",
            "aka_title.regex" => "The alternative title must be at least 3 letters long"
        ];
    }

}
