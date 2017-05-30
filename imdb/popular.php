<?php
	include("../api_start.php");
	
	$limit = $_GET["limit"];
	
	$listIds = exec("python IMDBAPI.py popular $limit");
	$exploded = explode(",", $listIds);
	$orderedListIds = array();
	foreach($exploded as $id) {
		$orderedListIds[] = str_ireplace("'", "", $id);
	}
	//$ids = "1";
	$result = $db->query("SELECT m.* FROM movies m WHERE m.imdb_id in ($listIds)");
	
	$response = array();
	
	while ($arr = $result->fetch_assoc()) {
		$index = array_search($arr["imdb_id"], $orderedListIds);
		//var_dump($index);
		$response[$index] = $arr;
// 		var_dump($arr);
	}
	
	$resp = $response;
	ksort($resp);

	$res = json_encode(utf8ize($resp));

	echo $res;
	
	
	?>