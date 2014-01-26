<?php
class response 
{
	public $successful = true;
	public $released;
	public $critic;
	public $fan;
	public $details;
};

$response = new response();

include('../util/config.php');
$mysqli = new mysqli(DB_HOST, DB_USER,DB_PASSWORD,DB_NAME);
header('Content-type: application/json');
$movieid= (isset($_POST['movieid'])) ? $mysqli->real_escape_string($_POST["movieid"]) : "";
	
$query ="SELECT * from movies WHERE movieid=".$movieid;
			//echo $query."<br>";
			if ($result =$mysqli->query($query)) {
				if($obj = $result->fetch_object()){
					$response->released=$obj->released;
					$response->critic=$obj->critic;
					$response->fan=$obj->fan;
					$response->details=$obj->details;
					
				}
			}
  

echo json_encode($response);
?>