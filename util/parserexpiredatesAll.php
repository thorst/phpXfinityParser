<?php


//Expand the defaults so this script can run...
ini_set('max_execution_time', 5);//set this to 0 for indefinately, not recommedned
ini_set('memory_limit', '-1');


// Include the config options and parser library
include('config.php');
include('helper.php');

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
		$substr =  curl(Xf_ROOT.$row->href);
		if (!$substr) {
			echo "Failed getting stream for ".$row->title."<br>";
			continue;
		}
		
		//Parse them
		$dom = new DOMDocument;
		@$dom->loadHTML($substr);
		$xpath = new DomXpath($dom);
		$div = $xpath->query('//*[@class="video-data"]');
		
		$expires = "null";
		if($div->length > 0) {
			$div = $div->item(0);
			$temp_expires= $div->getAttribute('data-cim-video-expiredate');
			$temp_expires = strtotime($temp_expires);
			$temp_expires = date('Y-m-d',$temp_expires);
			$temp_expires = new DateTime($temp_expires);
		
			//If it expires over x years from now assume its always available
			if ($now->diff($temp_expires)->days < Xf_EXPYEAR*365) {
				$expires ="'".$temp_expires->format('Y-m-d')."'";
			}
		}
		
		$fan = "null";
		$critic = "null";
		$div = $xpath->query('//*[@class="rotten-tomatoes-rating"]');
		if($div->length > 0) {
			$div = $div->item(0);
			$fan= $div->getAttribute('data-urnrtfansummaryscore');
			$critic= $div->getAttribute('data-urnrtcriticsummaryscore');
			if ($fan==-1) {$fan="null";}
			if ($critic==-1) {$critic="null";}
		}
		
		
		$desc="null";
		$div = $xpath->query('//*[@class="details-description"]');
		if($div->length > 0) {
			$div = $div->item(0);
			$desc= $mysqli->real_escape_string($div->nodeValue);
		}
		
		
		$query = "UPDATE movies SET expires=".$expires.", fan=".$fan.",critic=".$critic.", details='".$desc."' WHERE movieid=".$row->movieid;
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