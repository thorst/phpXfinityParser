<?php

	header('Content-type: application/json');
	class response 
	{
		public $successful = false;
	};
	$response = new response();

	if(isset($_POST["email"]) && isset($_POST["password"])) {
        $email = $_POST["email"];
        $password = $_POST["password"];
    
        $query = $connection->prepare("SELECT `user_id` FROM `users` WHERE `email` = ? and `user_password` = PASSWORD(?)");
        $query->bind_param("ss", $email, $password);
        $query->execute();
        $query->bind_result($userid);
        $query->fetch();
        $query->close();
        
        if(!empty($userid)) {
            session_start();
            $_SESSION["authenticated"] = 'true';
			$response->successful=true;
			echo json_encode($response);
			exit();
        }  
    }
	echo json_encode($response);
?>