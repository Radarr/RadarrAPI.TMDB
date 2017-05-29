<?php
	$try_get_ids = true;
	include("../api_start.php");
	//$ids = "1";
	$result = $db->query("SELECT * FROM (SELECT m.*, dates.tmdbid, dates.type, dates.release_date as physical_release, dates.note as physical_release_note FROM movies as m, release_dates as dates WHERE (m.id = dates.tmdbid) AND (dates.`type` in (4,5,6)) AND (dates.release_date < DATE_ADD(CURRENT_TIMESTAMP, INTERVAL 21 day)) AND (dates.release_date > DATE_ADD(CURRENT_TIMESTAMP, INTERVAL -7 day)) AND (m.id NOT IN ($ids,$ignoredIds)) ORDER BY physical_release ASC) sub GROUP BY sub.id ORDER BY sub.popularity DESC");
	
	$response = array();
	
	while ($arr = $result->fetch_assoc()) {
		$response[] = $arr;
// 		var_dump($arr);
	}

	$res = json_encode(utf8ize($response));

	echo $res;

	
	//
	?>