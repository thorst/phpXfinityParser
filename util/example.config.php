<?php

/* MySQL settings */
define( 'DB_NAME',     	'My_DB' );
define( 'DB_USER',     	'My_User' );
define( 'DB_PASSWORD', 	'My_Password' );
define( 'DB_HOST', 		'localhost' );		

/*Xfinity settings*/
define( 'Xf_ROOT',		'http://xfinitytv.comcast.net');
define( 'Xf_WIDGET',	Xf_ROOT.'/movie.widget');
define( 'PAY_CODES',	"'d','f','e','h','cj'");			//These are the provider codes comcast uses to determine they are pay movies, 
															//This is configurable in case i messed up which codes they use, or they add/remove
define( 'Xf_EXPYEAR',	2);									//This is the number of years that we just assume the movie never expires.
															//Movies have expiration dates that go all across the board.
															//From empty, epoch, 2020, 2099, etc.

/*
The movie.widget returns different data sets, im guessing depending on region.
I tested with 4 servers. 3 returned the same dataset, the 4th returned a different
set. The 4th set was about 150 less movies. Typically half are free. So you could 
miss out on 75 movies. Because of this i have a proxy, that the parser calls. So you
can host your proxy on 1 or more servers. The returned data will be returned and deduped.
*/
static $WidgetServices = array(
	"http://localhost/xfinity/util/parserwidget.php"
);
?>