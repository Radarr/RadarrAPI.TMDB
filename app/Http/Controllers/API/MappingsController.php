<?php

namespace App\Http\Controllers\API;

use App\Event;
use App\EventType;
use App\Http\Requests\MappingAddRequest;
use App\Http\Requests\MappingFindRequest;
use App\Http\Requests\MappingGetRequest;
use App\Mapping;
use App\MappingMovie;
use App\Movie;
use App\TitleInfo;
use App\YearInfo;
use Carbon\Carbon;
use Helper;
use Illuminate\Support\Facades\Cache;
use Symfony\Component\HttpFoundation\Response;

class MappingsController extends JSONController
{
    /**
     * Returns a json string for api usage.
     *
     * @param  int  $id
     *
     * @return Response
     */
    public function get(MappingGetRequest $request)
    {
        $id = $request->query('id');

        $mapping = Mapping::find($id);

        if ($mapping == null) {
            abort(404, "Mapping with id $id was not found. Maybe it was removed?");
        }

        return response()->json($mapping)->header('Access-Control-Allow-Origin', '*');
    }

    public function find(MappingFindRequest $request)
    {
        $tmdbid = $request->query('tmdbid');
        $movie = MappingMovie::find($tmdbid);

        if ($movie == null) {
            abort(404, "Movie with tmdbid $tmdbid was not found. Either it does not exist or no mappings have been added yet.");
        }

        $type = $request->query('type');
        $language = $request->query('language', 'en');

        $titles = [];
        $years = [];

        if ($type == 'title' || $type == 'all' || $type == null) {
            $titles = Mapping::where('tmdbid', '=', $tmdbid)->where('info_type', '=', 'title')->whereHas('title_info', function ($query) use ($language) {
                $query->where('language', '=', $language);
            })->get()->toArray();
        }

        if ($type == 'year' || $type == 'all' || $type == null) {
            $years = Mapping::where('tmdbid', '=', $tmdbid)->where('info_type', '=', 'year')->get()->toArray();
        }

        $movie['mappings'] = ['titles' => $titles, 'years' => $years];

        return response()->json($movie)->header('Access-Control-Allow-Origin', '*');
    }

    public function add(MappingAddRequest $request)
    {
        $tmdbid = $request->query('tmdbid');
        $type = $request->query('type');

        //Ensure that the movie is in our mapping database!
        if (!MappingMovie::find($tmdbid)) {
            $movie = Movie::find($tmdbid);
            if ($movie == null) {
                abort(422, 'The movie with the given tmdbid could not be found!');
            }
            $movie->createMappingMovie()->save();
        }

        $existing = false;
        $info = null;

        if ($type == 'title') {
            $aka_title = $request->get('aka_title');
            $title_language = $request->get('language', 'en');
            $aka_clean_title = Helper::clean_title($aka_title);
            $existing = Mapping::whereHas('title_info', function ($query) use ($aka_clean_title) {
                $query->where('aka_clean_title', '=', $aka_clean_title);
            })->first();
            $info = new TitleInfo(['aka_title' => $aka_title, 'aka_clean_title' => $aka_clean_title, 'language' => $title_language]);
        } else {
            $aka_year = $request->get('aka_year');
            $existing = Mapping::whereHas('year_info', function ($query) use ($aka_year) {
                $query->where('aka_year', '=', $aka_year);
            })->first();
            $info = new YearInfo(['aka_year' => $aka_year]);
        }

        if ($existing != null && $existing != false) {
            $existing->vote();

            return response()->json($existing);
        }

        $info->save();

        $mapping = new Mapping(['tmdbid' => $tmdbid, 'info_type' => $type, 'info_id' => $info->id]);

        $mapping->save();

        $mapping->info = $info;

        $mapping->votes = 1;
        $mapping->vote_count = 1;
        $mapping->locked = false;

        $event = new Event(['type' => EventType::AddedMapping, 'mappings_id' => $mapping->id, 'ip' => md5($_SERVER['REMOTE_ADDR'])]);
        $event->save();

        return response()->json($mapping)->header('Access-Control-Allow-Origin', '*');
    }

    public function vote(MappingGetRequest $request)
    {
        $id = $request->query('id');
        $direction = $request->query('direction');
        if (!isset($direction) || $direction > 1) {
            $direction = 1;
        }

        if ($direction < 1) {
            $direction = -1;
        }
        $mapping = Mapping::find($id);

        if ($mapping == null) {
            abort(404, "Mapping with id $id was not found. Maybe it was removed?");
        }

        $mapping->vote($direction);

        return response()->json($mapping)->header('Access-Control-Allow-Origin', '*');
    }

    public function latest()
    {
        $events = Cache::remember('mappings.latest', Carbon::now()->addMinutes(1), function () {
            return Event::where('type', '=', 0)->orderByDesc('date')->limit(5)->get()->toArray();
        });

        return response()->json($events)->header('Access-Control-Allow-Origin', '*');
    }
}
