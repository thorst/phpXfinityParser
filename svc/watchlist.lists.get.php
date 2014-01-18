<?php
class response 
{
	public $successful = true;
	public $watchlists = array();
};
class watchlist 
{
	public $id;
	public $name;
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
	
	header('Content-type: application/json');


   
   
			$query ="SELECT * from userwatchlists WHERE user_id=".$user_id;
			//echo $query."<br>";
			if ($result =$mysqli->query($query)) {
				while($obj = $result->fetch_object()){
					
					$list = new watchlist();
					
					$list->name = $obj->name;
					$list->id = $obj->userwatchlist_id;
					
					array_push($response->watchlists,$list);
				}
			}
  
	
//}
echo json_encode($response);
?>