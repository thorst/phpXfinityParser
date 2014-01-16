<html>
<head></head>
<body>
<?php
		
		
//Expand the defaults so this script can run...
ini_set('max_execution_time', 0);
ini_set('memory_limit', '-1');


// Include the config options and parser library
include('config.php');
include('helper.php');


//Define movie class
class movie
{
	public $movieid="null";
    public $title="null";
    public $href="null";
	public $comcastid="null";
	public $provcodes="null";
	public $inserted="null";
	public $updated="null";
	public $released="null";
	public $expires="null";
	public $critic="null";
	public $fan="null";
	public $details="null";
};

//Connect to the db
$mysqli = new mysqli(DB_HOST, DB_USER,DB_PASSWORD,DB_NAME);

//Connect to our web services to grab the json (parsed movies.widget)
$movies = array();
foreach ($WidgetServices as $e) {
	$str =  curl($e);
	$movies =array_merge($movies,json_decode($str));
}
$movies = array_unique($movies, SORT_REGULAR);


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
	$query ="UPDATE movies SET updated='".$currenttime."', code='".$m->provcodes."' WHERE comcastid=".$m->comcastid;
	if ($mysqli->query($query)) {
		
		//If no rows were affected
		if ($mysqli->affected_rows==0) {
			echo "Inserting ".$m->title."<br>";
			$insertCount++;
		
			//If this isnt the first load then get the expire datetime
			if (!$initialload) {
				$substr =  curl(Xf_ROOT.$m->href);
				$dom = new DOMDocument;
				@$dom->loadHTML($substr);
				$xpath = new DomXpath($dom);
				$div = $xpath->query('//*[@class="video-data"]');
				
				$m->expires = "null";
				if($div->length > 0) {
					$div = $div->item(0);
					$temp_expires= $div->getAttribute('data-cim-video-expiredate');
					$temp_expires = strtotime($temp_expires);
					$temp_expires = date('Y-m-d',$temp_expires);
					$temp_expires = new DateTime($temp_expires);
				
					//If it expires over x years from now assume its always available
					if ($now->diff($temp_expires)->days < Xf_EXPYEAR*365) {
						$m->expires ="'".$temp_expires->format('Y-m-d')."'";
					}
				}
				
				$div = $xpath->query('//*[@class="rotten-tomatoes-rating"]');
				if($div->length > 0) {
					$div = $div->item(0);
					$m->fan= $div->getAttribute('data-urnrtfansummaryscore');
					$m->critic= $div->getAttribute('data-urnrtcriticsummaryscore');
					if ($m->fan==-1) {$m->fan="null";}
					if ($m->critic==-1) {$m->critic="null";}
				}
				
				
				
				$div = $xpath->query('//*[@class="details-description"]');
				if($div->length > 0) {
					$div = $div->item(0);
					$m->details= "'".$mysqli->real_escape_string($div->nodeValue)."'";
				}
			
			//If this is the first load default it to a date we can figure out later
			} else {
				$m->expires="'1980-01-01'";
			}
			
			
			//Insert the data
			$query ="INSERT INTO movies (title, href,  comcastid, inserted, updated,expires,released,code,critic,fan,details) VALUES ('".$m->title."','".$m->href."',".$m->comcastid.",'".$currenttime."','".$currenttime."',".$m->expires.",".$m->released.",'".$m->provcodes."',".$m->critic.",".$m->fan.",".$m->details.")";
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