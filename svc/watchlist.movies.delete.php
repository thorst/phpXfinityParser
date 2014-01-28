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
	$watchlistmovies_id= (isset($_POST['watchlistmovies_id'])) ? $mysqli->real_escape_string($_POST["watchlistmovies_id"]) : "";
	
	
	header('Content-type: application/json');
	 


   
   
			$query ="delete w from   watchlistmovies w join userwatchlists u on w.userwatchlist_id=u.userwatchlist_id where w.watchlistmovies_id=".$watchlistmovies_id." and u.user_id=".$LoggedInResponse->user_id;
			//echo $query."<br>";
			if (!$mysqli->query($query)) {
				$response->error=$mysqli->error;
			} else {
				$response->successful=true;
			}
  
	
//}
echo json_encode($response);
?>