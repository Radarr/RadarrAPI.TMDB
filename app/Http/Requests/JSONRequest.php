<?php

namespace App\Http\Requests;

use App\Helpers\Helper;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Request;

class JSONRequest extends FormRequest
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
     * Get the proper failed validation response for the request.
     *
     * @param array $errors
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function response(array $errors)
    {
        if ($this->expectsJson()) {
            $json_errors = [];
            foreach ($errors as $key => $error) {
                $new_error = [];
                $new_error['id'] = Helper::generate_uuid_v4();
                $new_error['status'] = 422;
                $new_error['title'] = "Unprocessable Entity! Parameter {$key} has one or more errors.";
                $new_error['detail'] = "The following errors happened while processing the request parameter $key: ".implode(' ', $error);
                $json_errors[] = $new_error;
            }
            Log::warning('Unprocessable Entity (422)!', ['errors' => $json_errors, 'identification' => ['id' => $json_errors[0]['id']]]);

            return response()->json(['errors' => $json_errors], 422);
        }

        return $this->redirector->to($this->getRedirectUrl())
                                        ->withInput($this->except($this->dontFlash))
                                        ->withErrors($errors, $this->errorBag);
    }
}
