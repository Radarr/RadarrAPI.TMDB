<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\IMDBListRequest;
use Helper;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class IMDBController extends JSONController
{
    /**
     * Returns a json string for api usage.
     *
     * @param int $id
     *
     * @return Response
     */
    public function top250(Request $request)
    {
        $movies = Helper::get_from_imdb_py('/template/imdb-android-writable/6.4.list-top250-skeleton.jstl/render', '!all');

        return response()->json($movies);
    }

    public function popular(Request $request)
    {
        $limit = 10;
        if ($request->query('limit') != null) {
            $limit = $request->query('limit');
        }
        $movies = Helper::get_from_imdb_py('/template/imdb-android-writable/6.4.movies-popular-titles.jstl/render', 'ranks.!all.id');

        return response()->json($movies);
    }

    public function user_list(IMDBListRequest $request)
    {
        $listId = $request->query('listId');
        $path = '/lists/';
        if (stripos($listId, 'ur') !== false) {
            $path .= $listId.'/watchlist/';
        } else {
            $path .= $listId;
        }
        $movies = Helper::get_from_imdb_py($path, 'list.items.!all.entityId', 5);

        return response()->json($movies);
    }
}
