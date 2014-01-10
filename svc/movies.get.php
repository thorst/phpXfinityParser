<?php


header('Content-type: application/json');

include('../util/config.php');
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


$start= $mysqli->real_escape_string($_POST["start"]);
$end= $mysqli->real_escape_string($_POST["end"]);


if ($result = $mysqli->query("SELECT * FROM movies 
								Where 
									inserted > '".INITIAL_LOAD."'
									and inserted <= '".$start."'
									and inserted >= '".$end."'
								ORDER BY
									title
							")) {
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


echo json_encode($movies);

?>