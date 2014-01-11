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
	public $released;
	public $expires;
};
$movies = array(); 
$mysqli = new mysqli(DB_HOST, DB_USER,DB_PASSWORD,DB_NAME);

$limit = "";
$count=0;
$load= (isset($_POST['load'])) ? $mysqli->real_escape_string($_POST["load"]) : "";
$backupcount= (isset($_POST['count'])) ? $mysqli->real_escape_string($_POST["count"]) : 0;


//They passed in next load date, and it is the same as the initial load, add a limit
if ($load == INITIAL_LOAD) {
	$count=$backupcount;
	$limit = " LIMIT ".$count.",50";
	
	
//If they arent passing in when to load from, get most recent load
} else if ($load=="") {
	$query = "SELECT inserted from movies order by inserted desc limit 1,1";
	if ($result = $mysqli->query($query)) {
		$row = $result->fetch_array();
		$load = $row["inserted"];
	}

//They passed load date, so get next load time
} else {
	$query = "SELECT inserted from movies where inserted < '".$load."' order by inserted desc limit 1,1";
	if ($result = $mysqli->query($query)) {
		$row = $result->fetch_array();
		$load = $row["inserted"];
	}
}

//They past in not initial load, but we figured out the next load was the initial
if ($load == INITIAL_LOAD) {
	$count=$backupcount;
	$limit = " LIMIT ".$count.",50";
}

$query ="SELECT * FROM movies Where inserted = '".$load."' ORDER BY title".$limit;
//echo $query;
if ($result = $mysqli->query($query)) {
	$row_cnt = $result->num_rows;
	while($obj = $result->fetch_object()){
		$provcodes = array();
		 
		if ($result2 =$mysqli->query("SELECT * FROM movieprovcode  Where movieid =".$obj->movieid)) {
			while($obj2 = $result2->fetch_object()){
				array_push($provcodes,$obj2->provcode);
			}
		}
		$current = new movie();
		$current->title = $obj->title;
		$current->href = $obj->href;
		$current->id = $obj->comcastid;
		$current->added = date( 'm/d gA',strtotime($obj->inserted));
		$current->expires = date( 'm-d-Y',strtotime($obj->expires));
		$current->released = $obj->released;
		$current->codes = $provcodes;			//space seperated
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