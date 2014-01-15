<?php
//Expand the defaults so this script can run...
ini_set('max_execution_time', 0);
ini_set('memory_limit', '-1');


// Include the config options and parser library
include('config.php');


//Define movie class
class movie
{
	public $movieid;
    public $title;
    public $href;
	public $comcastid;
	public $provcodes;
	public $inserted;
	public $updated;
	public $released;
	public $expires;
};

//Connect to the db
$mysqli = new mysqli(DB_HOST, DB_USER,DB_PASSWORD,DB_NAME);
//Grab html
$str =  file_get_contents(Xf_WIDGET);


//For easier debugging we can save the html page and load from disk
//file_put_contents('sample.html', $str);
//$str = file_get_contents('sample.elementfx.html');


//native dom parser
//http://stackoverflow.com/questions/3820666/grabbing-the-href-attribute-of-an-a-element/3820783#3820783
$dom = new DOMDocument;
$dom->loadHTML($str);
$movies = array();
foreach ($dom->getElementsByTagName('a') as $e) {
	$current = new movie();
	$current->title = $mysqli->real_escape_string($e->nodeValue);
	$current->href = $e->getAttribute( 'href' );
	$current->id = $e->getAttribute( 'id' );
	$current->provcodes = $e->getAttribute('data-p');			//space seperated
	$current->released=$e->getAttribute('data-rl');
	if ($current->released=="") { $current->released = 'null'; }
	array_push($movies,$current);
}

//header('Content-type: application/json');
echo json_encode($movies);
?>