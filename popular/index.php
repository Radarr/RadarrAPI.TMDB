<?php
	$try_get_ids = true;
	include("../api_start.php");
	//$ids = "1";
	$result = $db->query("SELECT m.*, s.* FROM movies m, stevenlu s WHERE m.imdb_id = s.imdb_id AND m.id NOT IN ($ids,$ignoredIds) ORDER BY m.popularity DESC");
	
	$response = array();
	
	while ($arr = $result->fetch_assoc()) {
		$response[] = $arr;
// 		var_dump($arr);
	}

	$res = json_encode(utf8ize($response));

	echo $res;
	
	?>