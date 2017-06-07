<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Illuminate\Support\Facades\Log;

use App\Helpers\Helper;

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
     * @param  array  $errors
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function response(array $errors)
    {
        if ($this->expectsJson()) {
          $json_errors = array();
          foreach ($errors as $key => $error)
          {
              $new_error = array();
              $new_error["id"] = Helper::generate_uuid_v4();
              $new_error["status"] = 422;
              $new_error["title"] = "Unprocessable Entity! Parameter {$key} has one or more errors.";
              $new_error["detail"] = "The following errors happened while processing the request parameter $key: ".implode(" ", $error);
              $json_errors[] = $new_error;
          }
          Log::warning("Unprocessable Entity (422)!", array("errors" => $json_errors, "identification" => array("id" => $json_errors[0]["id"])));
          return new JsonResponse(array("errors" => $json_errors), 422);
        }
        return $this->redirector->to($this->getRedirectUrl())
                                        ->withInput($this->except($this->dontFlash))
                                        ->withErrors($errors, $this->errorBag);
    }

}
