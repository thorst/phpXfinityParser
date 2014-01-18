<?php 
//If this is a postback
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	//General Setup
	include('util/config.php');
	class response 
	{
		public $successful = false;
		public $password;
	};
	
	//Define variables
	$mysqli = new mysqli(DB_HOST, DB_USER,DB_PASSWORD,DB_NAME);
	$email = $_POST["email"];
	$password = $_POST["password"].SALT.$_POST["email"];
	$response = new response();
	
	//Generate Password
	$query = $mysqli->prepare("select PASSWORD(?)");
	$query->bind_param("s", $password);
	$query->execute();
	$query->bind_result($response->password);
	$query->fetch();
	$query->close();

	//Return data
	header('Content-type: application/json');
	echo json_encode($response);
exit;
}
?>
<?php
include("common.php");
renderHeader(""); 
?>


		<div role="form">
		  <div class="form-group">
			<label for="txEmail">Email address</label>
			<input type="email" class="form-control" id="txEmail" placeholder="Enter email">
		  </div>
		  <div class="form-group">
			<label for="txPassword">Password</label>
			<input type="password" class="form-control" id="txPassword" placeholder="Password">
		  </div>
		</div>
		
		
        <button type="button" class="btn btn-success" id="btCreatePassword">Generate</button>
		
		<h1 id="output"></h1>
		
		<script>
	$(function() {
		$("#btCreatePassword").click(function(){
			var request ={
				email: $("#txEmail").val(),
				password: md5($("#txPassword").val())
			};
			$.when(
				$.ajax({
					url: "",
					type: "POST",
					data: request
				})
			).done(function(data) {
				$("#output").html(data.password);
				
			});
		});
	});
	
	
	</script>
		
<?php renderFooter(); ?>