<?php
include('../util/config.php');
include('../util/helper.php');
header('Content-type: application/json');


class response 
{
	public $successful;
	public $error;
};
$response = new response();
$mysqli = new mysqli(DB_HOST, DB_USER,DB_PASSWORD,DB_NAME);

$limit = "";
$count=0;
$email= (isset($_POST['email'])) ? $mysqli->real_escape_string($_POST["email"]) : "";

$password = substr(md5(rand()), 0, 10);
$md5password = md5($password);
$saltedpassword = $md5password.SALT.$_POST["email"];


//If they arent passing in when to load from, get most recent load

 $query = $mysqli->prepare("UPDATE `users` set `password` = PASSWORD(?)  WHERE `email` = ? ");
        $query->bind_param("ss",  $saltedpassword  ,$email);
        $query->execute();
		$cnt = $mysqli->affected_rows;
        $query->close();


if ($cnt>=1) {
$response->successful = true;

$headers  = 'MIME-Version: 1.0' . "\r\n";
$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
mail($email, 'Xfinity Forgot Password', $password,$headers);

}
echo json_encode($response);
?>