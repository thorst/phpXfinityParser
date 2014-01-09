<?php
include('function.find_date.php');
include('simple_html_dom.php');
//#actionPanelDetails
//[data-cim-entity-expires]
//$str =  file_get_contents('http://xfinitytv.comcast.net/watch/The-Whole-Town-s-Talking/7427569567882649112/movies');
//echo $str;
	$file = 'movie3.html';
	//file_put_contents($file, $str);
	$str = file_get_contents($file);

// Create a DOM object
$html = new simple_html_dom();
$html->load($str);
foreach($html->find('#actionPanelDetails') as $e) {
	
	echo $e->innertext;
	}
	echo "<br>";
	//
	foreach($html->find('[data-cim-entity-expires]') as $e) {
	echo "here";
	echo $e->innertext;
	}echo "<br>";echo "<br>";
	
	
foreach($html->find('.video-data') as $d) {
	
	echo $d->attr['data-cim-video-expiredate'];
	
	}
?>