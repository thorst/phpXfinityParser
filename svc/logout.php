<?php
class response 
{
	public $successful = true;
};

$response = new response();
header('Content-type: application/json');
include_once('../util/config.php');
//Connect to the db
$mysqli = new mysqli(DB_HOST, DB_USER,DB_PASSWORD,DB_NAME);
	
	
	session_start();
$session_key = session_id();

$query = $mysqli->prepare("DELETE FROM `sessions` WHERE `session_key` = ? AND `session_address` = ? AND `session_useragent` = ?");
$query->bind_param("sss", $session_key, $_SERVER['REMOTE_ADDR'], $_SERVER['HTTP_USER_AGENT']);
$query->execute();
$query->close();



echo json_encode($response);

?>