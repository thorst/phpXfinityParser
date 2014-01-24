<?php
class response 
{
	public $successful = true;
	public $watchlists = array();
};

$response = new response();

$mysqli = new mysqli(DB_HOST, DB_USER,DB_PASSWORD,DB_NAME);
header('Content-type: application/json');
$movieid= (isset($_POST['movieid'])) ? $mysqli->real_escape_string($_POST["movieid"]) : "";
	
$query ="SELECT * from movies WHERE movieid=".$movieid;
			//echo $query."<br>";
			if ($result =$mysqli->query($query)) {
				while($obj = $result->fetch_object()){
					
					$list = new watchlist();
					
					$list->name = $obj->name;
					$list->id = $obj->userwatchlist_id;
					
					array_push($response->watchlists,$list);
				}
			}
  

echo json_encode($response);
?>