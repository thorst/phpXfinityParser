<?php
class response 
{
	public $error;
	public $successful = false;
};
	$response = new response();

	
	include('../util/loggedIn.php');
$user_id =loggedIn();

if(empty($user_id)) {
	echo json_encode($response);
	exit;
}


//Connect to the db
$mysqli = new mysqli(DB_HOST, DB_USER,DB_PASSWORD,DB_NAME);
	$name= (isset($_POST['name'])) ? $mysqli->real_escape_string($_POST["name"]) : "";
	$userwatchlist_id= (isset($_POST['userwatchlist_id'])) ? $mysqli->real_escape_string($_POST["userwatchlist_id"]) : "";
	
	header('Content-type: application/json');
	 



   
   
			$query ="update  userwatchlists set name='".$name."' where userwatchlist_id=".$userwatchlist_id." and user_id=".$user_id;
			//echo $query."<br>";
			if (!$mysqli->query($query)) {
				$response->error=$mysqli->error;
			} else {
				$response->successful=true;
			}
  
	
//}
echo json_encode($response);
?>