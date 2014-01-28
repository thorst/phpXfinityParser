<?php
class response 
{
public $error;
	public $successful = false;
};
	$response = new response();

	
	include('../util/loggedIn.php');
$LoggedInResponse =loggedIn();

if(empty($LoggedInResponse->user_id)) {
	echo json_encode($response);
	exit;
}


//Connect to the db
$mysqli = new mysqli(DB_HOST, DB_USER,DB_PASSWORD,DB_NAME);
	$userwatchlist_id= (isset($_POST['userwatchlist_id'])) ? $mysqli->real_escape_string($_POST["userwatchlist_id"]) : "";
	
	
	header('Content-type: application/json');
	 



   
   
			$query ="delete from   watchlistmovies where userwatchlist_id=".$userwatchlist_id." and user_id=".$LoggedInResponse->user_id;
			//echo $query."<br>";
			if (!$mysqli->query($query)) {
				$response->error=$mysqli->error;
			} else {
				$response->successful=true;
			}
			
			$query ="delete from   userwatchlists where userwatchlist_id=".$userwatchlist_id;
			//echo $query."<br>";
			if (!$mysqli->query($query)) {
				$response->error=$mysqli->error;
			} else {
				$response->successful=true;
			}
	
//}
echo json_encode($response);
?>