<?php
	$try_get_ids = true;
	include("../api_start.php");
	
	$page_size = $_GET["page_size"];
	$page_size = ctype_digit($page_size) ? intval($page_size) : 50;
	
	set_time_limit (300);
	
	function score ($elem) {
		global $count_max, $pop_max, $vote_max;
		
		return (float)($elem["vote_average"]) / ($vote_max) + (float)($elem["popularity"]) / (2*$pop_max) + (float)($elem["countO"]) / (2*$count_max);
	}
	
	function cmp ($a, $b) {
		global $count_max, $pop_max, $vote_max;
		
		if (score($a) > score($b)) {
			return 1;
		} else {
			return -1;
		}
	}
	


	function maximum($arr, $key) {
		$max = 0.0;
		
		foreach ($arr as $elem) {
			$val = $elem[$key];
			if ((double)$val > $max) {
				$max = $val;
			}
		}
		
		return $max;
	}
	
	$result = $db->query("SELECT mo.id, mo.popularity, mo.imdb_id, mo.title, mo.overview, mo.vote_average, mo.vote_count, mo.tagline, mo.poster_path, mo.release_date, mo.release_year, mo.trailer_key, mo.trailer_site, mo.backdrop_path, mo.homepage, mo.runtime, mo.countO FROM ( SELECT m.*, r.recommended_id, r.tmdbid, r.id as rid, count(m.id) as countO FROM movies m, recommendations r WHERE m.id = r.recommended_id AND r.tmdbid in ($ids) AND r.recommended_id not in ($ids,$ignoredIds) GROUP BY m.id ) as mo;");
	
	$response = array();
	
	while ($arr = $result->fetch_assoc()) {
		$response[] = $arr;
// 		var_dump($arr);
	}
	
	$count_max = maximum($response, "countO");
	$pop_max = maximum($response, "popularity");
	$vote_max = maximum($response, "vote_average");
	
	usort($response, "cmp");
	
	$response = array_reverse($response);
	
	$response = array_slice($response, 0, $page_size);
	
	$res = json_encode(utf8ize($response));

	echo $res;
	?>