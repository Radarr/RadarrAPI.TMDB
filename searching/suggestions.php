<?php

	include("../api_start.php");
	header("Access-Control-Allow-Origin: *");
	
	$term = $_GET["q"];
	
	if (!isset($term) || $term == "") {
		$term = $_GET["query"];

	}
	
	$term = clean_title(
		$term
	);
	
	$result = $db->query("SELECT m.id, m.title as name, m.release_year, m.poster_path FROM movies m WHERE m.clean_title LIKE '%$term%' ORDER BY m.popularity DESC LIMIT 5") or die(mysqli_error($db));
	
	$response = array();
	
	while ($arr = $result->fetch_assoc()) {
		//var_dump($index);
		$response[] = $arr;
// 		var_dump($arr);
	}
	
	$resp = $response;

	$res = json_encode(utf8ize($resp));

	echo $res;
	
	
	?>