<?php
class response 
{
	public $successful = true;
	
	public $movies = array();
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
	public $watchlistmovies_id;
};
	$response = new response();

	
	
	include('../util/loggedIn.php');
$LoggedInResponse =loggedIn();

if(empty($LoggedInResponse->user_id)) {
	echo json_encode($response);
	exit;
}



//Connect to the db
$mysqli = new mysqli(DB_HOST, DB_USER,DB_PASSWORD,DB_NAME);
	$userwatchlist_id= (isset($_POST['userwatchlist_id'])) ? $mysqli->real_escape_string($_POST["userwatchlist_id"]) : "";
	
	header('Content-type: application/json');




//if(!empty($session_id)) {
   
   
			//$query ="SELECT * from userwatchlists WHERE user_id=1";
			//echo $query."<br>";
			//if ($result =$mysqli->query($query)) {
			//	while($obj = $result->fetch_object()){
					//$movies = array();
					//$list = new watchlist();
					$query2 ="SELECT m.*,w.watchlistmovies_id from watchlistmovies w join movies m on w.movie_id=m.movieid join userwatchlists u on w.userwatchlist_id=u.userwatchlist_id  WHERE w.userwatchlist_id=".$userwatchlist_id." and u.user_id=".$LoggedInResponse->user_id." order by m.title";
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
							$current->watchlistmovies_id=$obj2->watchlistmovies_id;
							//$current->codes = $provcodes;			//space seperated
							$obj2->code!="" ? $current->codes = explode(" ",$obj2->code) : $current->codes =[];	
							array_push($response->movies,$current);
						}
					
					}
					//$list->name = $obj->name;
					//$list->id = $obj->userwatchlist_id;
					//$list->movies = $movies;
					//array_push($response->watchlists,$list);
			//	}
			//}
  
	
//}
echo json_encode($response);
?>