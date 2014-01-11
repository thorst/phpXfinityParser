<?php

/* MySQL settings */
define( 'DB_NAME',     	'My_DB' );
define( 'DB_USER',     	'My_User' );
define( 'DB_PASSWORD', 	'My_Password' );
define( 'DB_HOST', 		'localhost' );

/*Load settings*/
define( 'INITIAL_LOAD',	'2014-01-07 02:15:23');				//This is the date and time that shows up in the database for the initial load.
															//You wont know this value until you run util/parser.php the first time			

/*Xfinity settings*/
define( 'Xf_ROOT',		'http://xfinitytv.comcast.net');
define( 'Xf_WIDGET',	Xf_ROOT.'/movie.widget');
define( 'Xf_EXPYEAR',	2);									//This is the number of years that we just assume the movie never expires.
															//Movies have expiration dates that go all across the board.
															//From empty, epoch, 2020, 2099, etc.

?>