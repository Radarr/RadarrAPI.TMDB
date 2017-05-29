<?php
	//Initial Loading;
	$ini_data = file_get_contents(dirname(__FILE__)."/ini.json");
	$INI = json_decode($ini_data, true);
	
	
	header('Content-type: application/json');
	
	$json_post = file_get_contents('php://input');
	$JPOST = json_decode($json_post, true);
	
	function utf8ize($mixed) {
		if (is_array($mixed)) {
			foreach ($mixed as $key => $value) {
        		$mixed[$key] = utf8ize($value);
    		}
		} else if (is_string ($mixed)) {
			return utf8_encode($mixed);
		}
		
		return $mixed;
	}
	
	$tmdb_mysql = $INI["tmdb_mysql"];
	$tmdbt_mysql = $INI["tmdbt_mysql"];
	
	$db = new mysqli($tmdb_mysql["url"], $tmdb_mysql["user"], $tmdb_mysql["pass"], $tmdb_mysql["db"]);
	//$db = new mysqli($tmdbt_mysql["url"], $tmdbt_mysql["user"], $tmdbt_mysql["pass"], $tmdbt_mysql["db"]); Used for tests or for database maintanance
	
	$db->query("SET sql_mode='';");
	mysqli_set_charset("utf8");
	
	
	//Used for list api
	if ($try_get_ids) {
		$ids = "";
		$ids = $db->escape_string($_GET["tmdbids"]);
		if (!isset($ids) || $ids === "") {
			$ids = $db->escape_string($_POST["tmdbids"]);
		}
		if (!isset($ids) || $ids === "") {
			$ids = "1";
		} 
		
		$ignoredIds = "";
		$ignoredIds = $db->escape_string($_GET["ignoredIds"]);
		if (!isset($ignoredIds) || $ignoredIds === "") {
			$ignoredIds = $db->escape_string($_POST["ignoredIds"]);
		}
		if (!isset($ignoredIds) || $ignoredIds === "") {
			$ignoredIds = "1";
		}
	}

	?>