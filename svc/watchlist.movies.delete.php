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
	$watchlistmovies_id= (isset($_POST['watchlistmovies_id'])) ? $mysqli->real_escape_string($_POST["watchlistmovies_id"]) : "";
	
	
	header('Content-type: application/json');
	 


session_start();
$session_key = session_id();

//if(!empty($session_id)) {
   
   
			$query ="delete from   watchlistmovies where watchlistmovies_id=".$watchlistmovies_id;
			//echo $query."<br>";
			if (!$mysqli->query($query)) {
				$response->error=$mysqli->error;
			} else {
				$response->successful=true;
			}
  
	
//}
echo json_encode($response);
?>