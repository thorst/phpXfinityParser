<?php
/*
Different servers will pull different data from the widget. I had 2 different files, spanning 4 servers.
*/

//Your two files
$str = file_get_contents('sample.elementfx.html');
$mine = file_get_contents('sample.mine.html');

$dom = new DOMDocument;
$dom->loadHTML($mine);
$mymovies = array();
foreach ($dom->getElementsByTagName('a') as $e) {
	array_push($mymovies,$e->getAttribute( 'id' ));
}

$dom = new DOMDocument;
$dom->loadHTML($str);
$fxmovies = array();
foreach ($dom->getElementsByTagName('a') as $e) {
	array_push($fxmovies,$e->getAttribute( 'id' ));
}

echo "In mine but not fx<br>";
foreach ($mymovies as &$value) {
	if (!in_array($value, $fxmovies)) {
		echo $value."<br>";
	}
}
echo "<br>";
echo "In fx but not mine<br>";
foreach ($fxmovies as &$value) {
	if (!in_array($value, $mymovies)) {
		echo $value."<br>";
	}
}
?>