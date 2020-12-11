<?php

namespace App\Helpers;

use App\Mapping;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;

$mappingsStorageDate = Carbon::now()->addHours(4);

class MappingsCache
{
    /**
     * Remembers a query for retrieving mapping objects in the cache. Automatically get's updated on voting as well as new additions.
     *
     * @param associative array $query Array containing keys and values to filter mappings by
     *
     * @return array or object        array of mappings
     */
    /*
    id-11, tmdbid-11, imdbid-11
    tmdbid-11-type-title
     */
    public static function rememberQuery($query)
    {
        global $mappingsStorageDate;

        if ($mappingsStorageDate == null) {
            $mappingsStorageDate = Carbon::now()->addHours(4);
        }

        //Cache::flush();

        $cache_key = MappingsCache::buildKeyFromQuery($query);

        if (Cache::has($cache_key)) {
            return Cache::get($cache_key);
        }

        $results = Mapping::where($query)->get()->toArray();

        Cache::put($cache_key, $results, $mappingsStorageDate);

        if (count($results) == 0) {
            return $results;
        }

        $tmdbid = 0;
        $imdbid = '';

        if (!array_key_exists('tmdbid', $results) && !array_key_exists('id', $query)) {
            if (!array_key_exists('tmdbid', $query)) {
                $tmdbid = $results[0]['tmdbid'];
            }

            if (!array_key_exists('imdbid', $query)) {
                $imdbid = $results[0]['imdbid'];
            }
        }

        $title_mappings = [];

        $year_mappings = [];

        foreach ($results as $mapping) {
            $type = $mapping['mapable_type'];
            if ($type == 'title') {
                $title_mappings[] = $mapping;
            } elseif ($type == 'year') {
                $year_mappings[] = $mapping;
            }

            Cache::put(MappingsCache::buildKeyFromQuery(['id' => $mapping['id']]), $mapping, $mappingsStorageDate);
        }

        $to_cache = [];

        $tmdb_query = ['tmdbid' => $tmdbid];
        $imdb_query = ['imdbid' => $imdbid];

        if (array_key_exists('mapable_type', $query)) {
            $tmdb_query['mapable_type'] = $query['mapable_type'];
            $imdb_query['mapable_type'] = $query['mapable_type'];

            if ($tmdbid !== 0) {
                $to_cache[] = [$tmdb_query, $results];
            }

            if ($imdbid !== '') {
                $to_cache[] = [$imdb_query, $results];
            }
        } else {
            if ($tmdbid !== 0) {
                $to_cache[] = [$tmdb_query, $results];
            }

            $tmdbid = $results[0]['tmdbid'];
            $tmdb_query = ['tmdbid' => $tmdbid];
            $tmdb_query['mapable_type'] = 'title';
            $to_cache[] = [$tmdb_query, $title_mappings];
            $tmdb_query['mapable_type'] = 'year';
            $to_cache[] = [$tmdb_query, $year_mappings];

            if ($imdbid !== '') {
                $to_cache[] = [$imdb_query, $results];
            }

            $imdbid = $results[0]['imdbid'];
            $imdb_query = ['imdbid' => $imdbid];
            $imdb_query['mapable_type'] = 'title';
            $to_cache[] = [$imdb_query, $title_mappings];
            $imdb_query['mapable_type'] = 'year';
            $to_cache[] = [$imdb_query, $year_mappings];
        }

        foreach ($to_cache as $item) {
            Cache::put(MappingsCache::buildKeyFromQuery($item[0]), $item[1], $mappingsStorageDate);
        }

        //dd($to_cache);

        return $results;
    }

    public static function updateMapping(Mapping $mapping)
    {
        $possible_storage_locations = [
            'tmdbid' => $mapping->tmdbid,
            'imdbid' => $mapping->imdbid,
        ];

        $possible_object_location = [
            'id' => $mapping->id,
        ];

        $type = $mapping->mapable_type;

        MappingsCache::updateMappingWithQuery($mapping, $possible_object_location);

        foreach ($possible_storage_locations as $key => $value) {
            MappingsCache::updateMappingWithQuery($mapping, [$key => $value]);
            MappingsCache::updateMappingWithQuery($mapping, [$key => $value, 'mapable_type' => $type]);
        }
    }

    private static function updateMappingWithQuery(Mapping $mapping, $query)
    {
        global $mappingsStorageDate;

        if ($mappingsStorageDate == null) {
            $mappingsStorageDate = Carbon::now()->addHours(4);
        }

        $key = MappingsCache::buildKeyFromQuery($query);
        if (Cache::has($key)) {
            $results = Cache::get($key);
            $newResults = [];

            if (array_key_exists('id', $results)) {
                Cache::put($key, $mapping->toArray(), $mappingsStorageDate);

                return;
            }

            foreach ($results as $result) {
                if ($result['id'] == $mapping->id) {
                    $newResults[] = $mapping->toArray();
                } else {
                    $newResults[] = $result;
                }
            }

            Cache::put($key, $newResults, $mappingsStorageDate);
        }
    }

    private static function buildKeyFromQuery($query, $key_seperator = '-', $seperator = '-', $prefix = 'mappings')
    {
        $cache_key = '';
        foreach ($query as $key => $value) {
            $cache_key .= $seperator.$key.$key_seperator.$value;
        }

        $pos = strpos($cache_key, $seperator);
        if ($pos !== false) {
            $cache_key = substr_replace($cache_key, '', $pos, strlen($seperator));
        }

        return "$prefix.".$cache_key;
    }
}
