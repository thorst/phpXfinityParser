<?php


function loggedIn() {
	include_once('config.php');
	
	//Connect to the db
	$mysqli = new mysqli(DB_HOST, DB_USER,DB_PASSWORD,DB_NAME);
	
	//Get session id
	session_start();
	$session_key = session_id();

	//Get users session
	$query = $mysqli->prepare("SELECT `session_id`, `user_id` FROM `sessions` WHERE `session_key` = ? AND `session_address` = ? AND `session_useragent` = ?");
	$query->bind_param("sss", $session_key, $_SERVER['REMOTE_ADDR'], $_SERVER['HTTP_USER_AGENT']);
	$query->execute();
	$query->bind_result($session_id, $user_id);
	$query->fetch();
	$query->close();

	//Return the users id
	return $user_id;
}

?>