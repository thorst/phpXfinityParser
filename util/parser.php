<link href="//netdna.bootstrapcdn.com/bootstrap/3.0.3/css/bootstrap.min.css" rel="stylesheet">

<?php

// Include the parser library
include('simple_html_dom.php');
include('movie.class.php');
include('config.php');


//Grab html
$str =  file_get_contents('http://xfinitytv.comcast.net/movie.widget');

$debug =false;
if ($debug) {
	//write to file
	$file = 'sample.html';
	//file_put_contents($file, $str);
	$str = file_get_contents($file);
}

// Create a DOM object
$html = new simple_html_dom();
$html->load($str);

// Find all "A" tags 
$movies = array();
foreach($html->find('a') as $e) {
	$current = new movie();
	$current->title = $e->innertext;
	$current->href = $e->href;
	$current->pop = intval($e->{'data-pop'});
	$current->id = $e->{'id'};
	$current->provcodes = $e->{'data-p'};			//space seperated
	$current->networkid = $e->{'data-n'};			//space seperated
	$current->latestnetworkid = $e->{'data-ln'};
	$current->released=$e->{'data-rl'};
	array_push($movies,$current);
}


//http://xfinitytv.comcast.net/api/entity/thumbnail/8495327261915294112/180/240?noRedir=true
//http://xfinitytv.comcast.net/api/entity/thumbnail/5142259246923230112/360/480


//For each record insert into db
$mysqli = new mysqli(DB_HOST, DB_USER,DB_PASSWORD,DB_NAME);
$sql = array(); 
$mysqltime = date("Y-m-d H:i:s");
foreach($movies as $m){
	if ($result = $mysqli->query("SELECT * FROM movies WHERE comcastid=".$m->id)) {

		/* determine number of rows result set */
		$row_cnt = $result->num_rows;
		if ($row_cnt==0) {
		
			$substr =  file_get_contents($m->href);
			$subhtml = new simple_html_dom();
			$subhtml->load($substr);
			foreach($subhtml->find('.video-data') as $d) {
				$m->expires= $d->attr['data-cim-video-expiredate'];
			}
			
			$mysqli->query("INSERT INTO movies (title, href, pop, comcastid, inserted, updated) VALUES ('".$m->title."','".$m->href."','".$m->pop."',".$m->id.",'".$mysqltime."','".$mysqltime."')");
			$iid=$mysqli->insert_id;
			foreach(explode( ' ', $m->provcodes ) as $c){
				if ($c!="") {$mysqli->query("INSERT INTO movieprovcode (movieid, provcode) VALUES (".$iid.",'".$c."' )");}
			}
		} else {
			$row = mysqli_fetch_array($result);
	
			$mysqli->query("UPDATE movies SET updated='".$mysqltime."', removed=null WHERE comcastid=".$m->id);
			//$mysqli->query("DELETE FROM movieprovcode WHERE movieid=".$row['movieid']);
			foreach(explode( ' ', $m->provcodes ) as $c){
				if ($c!="") {
				$mysqli->query("INSERT INTO movieprovcode (movieid, provcode) VALUES (".$row['movieid'].",'".$c."' )");
				}
			}
		}
	}
}

//For all the movies that havent been updated
$mysqli->query("UPDATE movies SET removed='".$mysqltime."' WHERE updated!='".$mysqltime."'");
$mysqli->close();

if ($debug) {
	
	//These are pay codes
	$codes = array("d", "f",'e','h','cj');


	//Render
	echo "<table class='table table-hover'>";
	foreach($movies as $m){
		//only show if lastnetork isnt networkid
		//if ($m->networkid==$m->latestnetworkid) {continue;}
		
		//skip displaying some if they arent free
		//if (in_array($m->provcodes, $codes)) {continue;}
		
		//render some details
		echo "
			<tr>
				<td>".$m->title."</td>
				<td>".$m->provcodes."</td>
				<td>'".$m->networkid."'</td>
				<td>'".$m->latestnetworkid."'</td>
			</tr>
		";
	}
	echo "</table>";
}
?>