<?php
include('../util/config.php');
include('../util/helper.php');
header('Content-type: application/json');
include ('../util/loggedIn.php');
global $LoggedInResponse;
$LoggedInResponse =loggedIn();
//var_dump($LoggedInResponse);
class response 
{
	public $successful =false;
	public $error;
};
$response = new response();
$mysqli = new mysqli(DB_HOST, DB_USER,DB_PASSWORD,DB_NAME);

$limit = "";
$count=0;
$password= (isset($_POST['password'])) ? $mysqli->real_escape_string($_POST["password"]) : "";

//$md5password = md5($password);
$saltedpassword = $password.SALT.$LoggedInResponse->email;


//If they arent passing in when to load from, get most recent load
//echo $saltedpassword;
//echo $LoggedInResponse->email;
 $query = $mysqli->prepare("UPDATE `users` set `password` = PASSWORD(?)  WHERE `email` = ? ");
        $query->bind_param("ss",  $saltedpassword  ,$LoggedInResponse->email);
        $query->execute();
		$cnt = $query->affected_rows;
        $query->close();
//echo $query;
//echo $cnt;
if ($cnt>=1) {
$response->successful = true;

}
echo json_encode($response);
?>