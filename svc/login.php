<?php

include('../util/config.php');
	header('Content-type: application/json');
	class response 
	{
		public $successful = false;
		public $username;
	};
	$response = new response();

$mysqli = new mysqli(DB_HOST, DB_USER,DB_PASSWORD,DB_NAME);


					
   
			
					
				
	if(isset($_POST["email"]) && isset($_POST["password"])) {
        $email = $_POST["email"];
        $password = $_POST["password"].SALT.$_POST["email"];
		
		
        $query = $mysqli->prepare("SELECT `user_id` FROM `users` WHERE `email` = ? and `password` = PASSWORD(?)");
        $query->bind_param("ss", $email, $password);
        $query->execute();
        $query->bind_result($userid);
        $query->fetch();
        $query->close();
       
        if(!empty($userid)) {
            session_start();
			 $session_key = session_id();
          
			$query = $mysqli->prepare("INSERT INTO `sessions` ( `user_id`, `session_key`, `session_address`, `session_useragent`) VALUES ( ?, ?, ?, ? );");
            $query->bind_param("isss", $userid, $session_key, $_SERVER['REMOTE_ADDR'], $_SERVER['HTTP_USER_AGENT'] );
			$query->execute();
            $query->close();
			
			$response->username=$email;
			$response->successful=true;
			echo json_encode($response);
			exit();
        }  
    }
	echo json_encode($response);
?>