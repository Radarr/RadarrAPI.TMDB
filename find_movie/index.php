<?php
	include("../api_start.php");
	//$ids = "1";
	
	$clean_titles_post = [];
	$clean_titles_post = $_GET["clean_titles"];
	
	if (!isset($clean_titles_post) || !is_array($clean_titles_post)) {
		$clean_titles_post = $_POST["clean_titles"];
	}
	
	if (!isset($clean_titles_post) || !is_array($clean_titles_post)) {
		$clean_titles_post = $JPOST["clean_titles"];
	}
	
	if (!isset($clean_titles_post) || !is_array($clean_titles_post)) {
		$clean_titles_post = ["not_a_real_movie"];
	}
	
	$clean_titles = "";
	
	foreach ($clean_titles_post as $clean_title) {
		$clean_titles .= "'".$db->escape_string($clean_title) . "',";
	}
	
	$clean_titles = rtrim($clean_titles, ",");
	
	$result = $db->query("(SELECT m.*, alt.*, m.clean_title as real_clean_title FROM movies as m, alt_titles as alt WHERE (m.clean_title in ($clean_titles) AND (alt.`tmdbid` = m.id)) GROUP BY m.id) UNION (SELECT m.*, alt.*, alt.`clean_alt_title` as real_clean_title FROM movies as m, alt_titles as alt WHERE (alt.`clean_alt_title` in ($clean_titles)) AND (alt.`tmdbid` = m.id) GROUP BY m.id)") or die(mysqli_error($db));
	
	$response = array();
	
	while ($arr = $result->fetch_assoc()) {
		$response[] = $arr;
// 		var_dump($arr);
	}

	$res = json_encode(utf8ize($response));

	echo $res;
	
	?>