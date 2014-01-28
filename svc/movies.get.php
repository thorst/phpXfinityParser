<?php


header('Content-type: application/json');

include('../util/config.php');
class response 
{
	public $movies;
	public $load;
	public $count;
};
class movie
{
    public $title;
    public $href;
	public $id;
	public $codes;
	public $added;
	public $expires;
	public $movieid;
};
$movies = array(); 
$mysqli = new mysqli(DB_HOST, DB_USER,DB_PASSWORD,DB_NAME);

$limit = "";
$count=0;
$load= (isset($_POST['load'])) ? $mysqli->real_escape_string($_POST["load"]) : "";
$backupcount= (isset($_POST['count'])) ? $mysqli->real_escape_string($_POST["count"]) : 0;

	
	
//If they arent passing in when to load from, get most recent load

$query = "SELECT distinct(inserted) FROM `movies` order by inserted desc limit 0,2";
if ($load!="") {
	$query = "SELECT distinct(inserted) from movies where inserted < '".$load."' order by inserted desc limit 0,2";
}

if ($result = $mysqli->query($query)) {
	$row_cnt = $result->num_rows;
	
	if ($row_cnt==0) {						//IF this is the last date, set the count to be the backup
		$count=$backupcount;
	}
	if ($row_cnt==1 || $row_cnt==0) {		//If we are moving to the last date, or are on the last date, set the count
		$limit = " LIMIT ".$count.",50";
	} 
	
	if ($row_cnt>=1){						//If there was at least one day load it in
		$row = $result->fetch_array();
		$load = $row["inserted"];
	}
}

$query ="SELECT * FROM movies Where inserted = '".$load."' ORDER BY title".$limit;
if ($result = $mysqli->query($query)) {
	$row_cnt = $result->num_rows;
	while($obj = $result->fetch_object()){
		
		 
		
		$current = new movie();
		$current->movieid = $obj->movieid;
		$current->title = $obj->title;
		$current->href = $obj->href;
		$current->id = $obj->comcastid;
		$current->added = date( 'm/d gA',strtotime($obj->inserted));
		//if ($obj->expires!=null) {$current->expires = date( 'm-d-Y',strtotime($obj->expires));}
		$current->released = $obj->released;
		$obj->code!="" ? $current->codes = explode(" ",$obj->code) : $current->codes =[];			//space seperated
		array_push($movies,$current);
    } 
	$result->close();
}

$response = new response();
$response->load=$load;
$response->movies=$movies;
$response->count=$row_cnt + $count;
echo json_encode($response);

?>