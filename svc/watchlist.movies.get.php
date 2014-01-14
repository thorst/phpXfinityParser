<?php
class response 
{
	public $successful = true;
	
	public $watchlists = array();
};
class watchlist 
{
	public $name;
	public $id;
	public $movies;
};
class movie
{
    public $title;
    public $href;
	public $id;
	public $codes;
	public $added;
	public $released;
	public $expires;
	public $movieid;
};
	$response = new response();
include('../util/config.php');
//Connect to the db
$mysqli = new mysqli(DB_HOST, DB_USER,DB_PASSWORD,DB_NAME);
	
	header('Content-type: application/json');



session_start();
$session_key = session_id();

//if(!empty($session_id)) {
   
   
			$query ="SELECT * from userwatchlists WHERE user_id=1";
			//echo $query."<br>";
			if ($result =$mysqli->query($query)) {
				while($obj = $result->fetch_object()){
					$movies = array();
					$list = new watchlist();
					$query2 ="SELECT m.* from watchlistmovies w join movies m on w.movie_id=m.movieid  WHERE userwatchlist_id=1";
					//echo $query2."<br>";
					if ($result2 =$mysqli->query($query2)) {
						
						while($obj2 = $result2->fetch_object()){
							$current = new movie();
							$current->movieid = $obj2->movieid;
							$current->title = $obj2->title;
							$current->href = $obj2->href;
							$current->id = $obj2->comcastid;
							$current->added = date( 'm/d gA',strtotime($obj2->inserted));
							if ($obj2->expires!=null) {$current->expires = date( 'm-d-Y',strtotime($obj2->expires));}
							$current->released = $obj2->released;
							//$current->codes = $provcodes;			//space seperated
							array_push($movies,$current);
						}
					
					}
					$list->name = $obj->name;
					$list->id = $obj->userwatchlist_id;
					$list->movies = $movies;
					array_push($response->watchlists,$list);
				}
			}
  
	
//}
echo json_encode($response);
?>