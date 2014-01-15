<?php
class response 
{
	public $successful = false;
};
	$response = new response();
include('../util/config.php');
//Connect to the db
$mysqli = new mysqli(DB_HOST, DB_USER,DB_PASSWORD,DB_NAME);
	$movieid= (isset($_POST['movieid'])) ? $mysqli->real_escape_string($_POST["movieid"]) : "";
	$listid= (isset($_POST['listid'])) ? $mysqli->real_escape_string($_POST["listid"]) : "";
	
	
	header('Content-type: application/json');



session_start();
$session_key = session_id();

//if(!empty($session_id)) {
   
   
			$query ="INSERT INTO watchlistmovies (userwatchlist_id, movie_id) VALUES (".$listid.",'".$movieid."')";
			//echo $query."<br>";
			if (!$mysqli->query($query)) {
				printf("Error Message: %s\n", $mysqli->error);
			} else {
				$response->successful=true;
			}
  
	
//}
echo json_encode($response);
?>