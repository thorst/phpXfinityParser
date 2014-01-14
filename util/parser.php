<html>
<head></head>
<body>
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
//$str = file_get_contents('sample.html');


//native dom parser
//http://stackoverflow.com/questions/3820666/grabbing-the-href-attribute-of-an-a-element/3820783#3820783
$dom = new DOMDocument;
$dom->loadHTML($str);
$movies = [];
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





//Determine if this is the initial load
$result = $mysqli->query("SELECT count(*) cnt FROM movies");
$row = mysqli_fetch_array($result);
$initialload= ($row['cnt']==0);


//For each a tag
$insertCount = 0;
$now = new DateTime();
$currenttime = date("Y-m-d H:i:s");
foreach($movies as $m){
	
	//Try to update the row in the database
	$query ="UPDATE movies SET updated='".$currenttime."', code='".$m->provcodes."' WHERE comcastid=".$m->id;
	if ($mysqli->query($query)) {
		
		//If no rows were affected
		if ($mysqli->affected_rows==0) {
			echo "Inserting ".$m->title."<br>";
			$insertCount++;
		
			//If this isnt the first load then get the expire datetime
			if (!$initialload) {
				$substr =  file_get_contents(Xf_ROOT.$m->href);
				$subhtml = new simple_html_dom();
				$subhtml->load($substr);
				$d = $subhtml->find('.video-data');//This and the next line can be chained in 5.4
				$d =$d[0];
				$m->expires= $d->attr['data-cim-video-expiredate'];
				$m->expires = strtotime($m->expires);
				$m->expires = date('Y-m-d',$m->expires);
				$m->expires = new DateTime($m->expires);
				
				//If it expires over x years from now assume its always available
				if ($now->diff($m->expires)->days > Xf_EXPYEAR*365) {
					$m->expires = "null";
				} else {
					$m->expires ="'".$m->expires->format('Y-m-d')."'";
				}
				
			
			//If this is the first load default it to a date we can figure out later
			} else {
				$m->expires="'1980-01-01'";
			}
			
			
			//Insert the data
			$query ="INSERT INTO movies (title, href,  comcastid, inserted, updated,expires,released,code) VALUES ('".$m->title."','".$m->href."',".$m->id.",'".$currenttime."','".$currenttime."',".$m->expires.",".$m->released.",'".$m->provcodes."')";
			//echo $query."<br>";
			if (!$mysqli->query($query)) {
				printf("Error Message: %s\n", $mysqli->error);
			}
			
			
		} //End Affected Rows
	} //End query succesful
} //End loop over a


//Delete all the movies that havent been updated
$query="DELETE
		FROM movies
		WHERE updated!='".$currenttime."'";
//echo $query."<br>";
if (!$mysqli->query($query)){
	printf("Error Message: %s\n", $mysqli->error);
}
echo "Deleted ".$mysqli->affected_rows."<br>";
echo "Inserted ".$insertCount."<br>";
echo "Movies ".count($movies);
//Close mysql
$mysqli->close();
?>
</body>
</html>