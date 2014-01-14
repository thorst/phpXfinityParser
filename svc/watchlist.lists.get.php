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
include('../util/config.php');
//Connect to the db
$mysqli = new mysqli(DB_HOST, DB_USER,DB_PASSWORD,DB_NAME);
	
	header('Content-type: application/json');



session_start();
$session_key = session_id();

//if(!empty($session_id)) {
   
   
			$query ="SELECT * from userwatchlists WHERE user_id=1";
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