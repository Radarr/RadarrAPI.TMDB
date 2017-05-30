<?php
	include("../api_start.php");
	
	
	$listId = $_GET["listId"];
	
	$listIds = exec("python IMDBAPI.py list $listId");
	//$ids = "1";
	$result = $db->query("SELECT m.* FROM movies m WHERE m.imdb_id in ($listIds)");
	
	$response = array();
	
	while ($arr = $result->fetch_assoc()) {
		$response[] = $arr;
// 		var_dump($arr);
	}

	$res = json_encode(utf8ize($response));

	echo $res;
	
	
	?>