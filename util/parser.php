<html>
<head></head>
<body>
<?php
		function curl($url){
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
            $data = curl_exec($ch);
            curl_close($ch);
            return $data;
        }
		
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

//Connect to our web services to grab the json (parsed movies.widget)
$movies = array();
foreach ($WidgetServices as $e) {
	$str =  file_get_contents($e);
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
	$query ="UPDATE movies SET updated='".$currenttime."', code='".$m->provcodes."' WHERE comcastid=".$m->id;
	if ($mysqli->query($query)) {
		
		//If no rows were affected
		if ($mysqli->affected_rows==0) {
			echo "Inserting ".$m->title."<br>";
			$insertCount++;
		
			//If this isnt the first load then get the expire datetime
			if (!$initialload) {
				$substr =  file_get_contents(Xf_ROOT.$m->href);
				$dom = new DOMDocument;
				@$dom->loadHTML($substr);
				$xpath = new DomXpath($dom);
				$div = $xpath->query('//*[@class="video-data"]');
				
				$expires = "null";
				if($div->length > 0) {
					$div = $div->item(0);
					$expires= $div->getAttribute('data-cim-video-expiredate');
					$expires = strtotime($expires);
					$expires = date('Y-m-d',$expires);
					$expires = new DateTime($expires);
				
					//If it expires over x years from now assume its always available
					if ($now->diff($expires)->days < Xf_EXPYEAR*365) {
						$expires ="'".$expires->format('Y-m-d')."'";
					}
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