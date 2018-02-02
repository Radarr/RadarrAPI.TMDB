<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Movie;
use Carbon\Carbon;
use Illuminate\Support\Facades\Input;

class MovieController extends Controller
{
    public function refresh_movies()
    {
        $ids = Input::get('ids');
        $movies = Movie::whereIn('id', $ids)->where('updated_at', '>', Carbon::now()->subDay());

        return $movies->paginate(250);
    }

    public function index(int $id)
    {
        return Movie::findOrFail($id);
    }
}
