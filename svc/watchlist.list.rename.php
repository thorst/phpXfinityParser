<?php
class response 
{
	public $error;
	public $successful = false;
};
	$response = new response();
include('../util/config.php');
//Connect to the db
$mysqli = new mysqli(DB_HOST, DB_USER,DB_PASSWORD,DB_NAME);
	$name= (isset($_POST['name'])) ? $mysqli->real_escape_string($_POST["name"]) : "";
	$userwatchlist_id= (isset($_POST['userwatchlist_id'])) ? $mysqli->real_escape_string($_POST["userwatchlist_id"]) : "";
	
	header('Content-type: application/json');
	 


session_start();
$session_key = session_id();

//if(!empty($session_id)) {
   
   
			$query ="update  userwatchlists set name='".$name."' where userwatchlist_id=".$userwatchlist_id;
			//echo $query."<br>";
			if (!$mysqli->query($query)) {
				$response->error=$mysqli->error;
			} else {
				$response->successful=true;
			}
  
	
//}
echo json_encode($response);
?>