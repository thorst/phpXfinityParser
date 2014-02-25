<?php
include('../util/config.php');
include('../util/helper.php');
header('Content-type: application/json');


class response 
{
	public $successful = false;
	public $error;
};
$response = new response();
$mysqli = new mysqli(DB_HOST, DB_USER,DB_PASSWORD,DB_NAME);

$limit = "";
$count=0;
$email= (isset($_POST['email'])) ? $mysqli->real_escape_string($_POST["email"]) : "";
$answer= (isset($_POST['answer'])) ? $mysqli->real_escape_string($_POST["answer"]) : "";
$question= (isset($_POST['question'])) ? $mysqli->real_escape_string($_POST["question"]) : "";

//Validate they didnt futz with the number to much

if ($answer < 0 || count($Questions)-1 <$answer) {
$response->error = "Incorrect answer.";
echo json_encode($response);
exit;
}

$allKeys = array_keys($Questions);
$key= $allKeys[$question];
if (strtolower($Questions[$key])!=strtolower($answer)) {
$response->error = "Incorrect answer.";
echo json_encode($response);
exit;
}

//make sure its new user
$query = $mysqli->prepare("SELECT user_id FROM `users` WHERE `email` = ? ");
        $query->bind_param("s",$email  );
        $query->execute();
		$query->store_result();
		$cnt = $query->num_rows;
        $query->close();
		
		if ($cnt>=1) {
		$response->error = "User already exists.";
echo json_encode($response);
exit;
		}

$password = substr(md5(rand()), 0, 10);
$md5password = md5($password);
$saltedpassword = $md5password.SALT.$_POST["email"];


//If they arent passing in when to load from, get most recent load

	$query = $mysqli->prepare("INSERT INTO `users` (`email`, `password`) VALUES ( ?, PASSWORD(?)) ");
        $query->bind_param("ss",$email,  $saltedpassword  );
        $query->execute();
		$cnt = $mysqli->affected_rows;
        $query->close();

if ($cnt>=1) {
$response->successful = true;

$headers  = 'MIME-Version: 1.0' . "\r\n";
$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
@mail($email, 'Xfinity Forgot Password', $password,$headers);

}
echo json_encode($response);
?>