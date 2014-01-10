<?php
//Expand the defaults so this script can run...
ini_set('max_execution_time', 0);
ini_set('memory_limit', '-1');


// Include the config options and parser library
include('config.php');
include('simple_html_dom.php');

//Connect to the db
$mysqli = new mysqli(DB_HOST, DB_USER,DB_PASSWORD,DB_NAME);

//Select movies that still have initial load as expire
$query ="SELECT href, movieid, title FROM movies WHERE expires ='1980-01-01';";
$updated = 0;
$now = new DateTime();
if ($result = $mysqli->query($query)) {
	
	while($row = $result->fetch_object()){
		echo "Updating ".$row->title."<br>";
		$updated++;
		
		//Get movie details
		$substr =  file_get_contents(Xf_ROOT.$row->href);
		if (!$substr) {
			echo "Failed getting stream";
			exit();
		}
		
		//Parse them
		$subhtml = new simple_html_dom();
		$subhtml->load($substr);
		
		//Find expires
		$d = $subhtml->find('.video-data')[0];
		$expires = $d->attr['data-cim-video-expiredate'];
		$expires = strtotime($expires);
		$expires = date('Y-m-d',$expires);
		$expires = new DateTime($expires);
		
		//If it expires over x years from now assume its always available
		if ($now->diff($expires)->days > Xf_EXPYEAR*365) {
			$expires = "null";
		} else {
			$expires ="'".$expires->format('Y-m-d')."'";
		}
		
		$query = "UPDATE movies SET expires=".$expires." WHERE movieid=".$row->movieid;
		//echo $query."<br>";
		if (!$mysqli->query($query)) {
			printf("Error Message: %s\n", $mysqli->error);
		}
	
	}
	
	$result->close();
}


$mysqli->close();

echo "Updated: ".$updated;
?>