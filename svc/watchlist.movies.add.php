<?php
class response 
{
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
	$movieid= (isset($_POST['movieid'])) ? $mysqli->real_escape_string($_POST["movieid"]) : "";
	$listid= (isset($_POST['listid'])) ? $mysqli->real_escape_string($_POST["listid"]) : "";
	
	
	header('Content-type: application/json');

$query ="Select count(*) cnt from userwatchlists where user_id=".$LoggedInResponse->user_id." and userwatchlist_id=".$listid;
			//echo $query."<br>";
			if ($result=$mysqli->query($query)) {
				$row = $result->fetch_array();
				if ($row["cnt"] !=1) {
					echo json_encode($response);
					exit;
				}
			} else {
				echo json_encode($response);
				exit;
			}
			
   
   
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