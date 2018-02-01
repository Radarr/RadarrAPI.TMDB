<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Person;
use Illuminate\Support\Facades\Input;

class PersonController extends Controller
{
    public function person_movies(int $id)
    {
        $person = Person::findOrFail($id);
        $query = $person->movies()->filter();

        $credit_type = Input::get('credit_type', null);
        if ($credit_type != null) {
            $query = $query->wherePivot('type', $credit_type);
        }
        $credit_department = Input::get('credit_department', null);
        if ($credit_department != null) {
            $deps = explode(',', $credit_department);
            $query = $query->wherePivotIn('department', $deps);
        }

        return $query->get();
    }
}
