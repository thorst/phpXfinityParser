<?php include("common.php");
renderHeader(""); 

global $LoggedInResponse;
if(empty($LoggedInResponse->user_id)) {
	echo "<h1>Please sign in first</h1>";
	
	renderFooter(); 
	exit;
}

?>
<h2>Reset Password</h2>
<div role="form">
  <div class="form-group">
    <label for="exampleInputEmail1">Email address</label>
    <input type="email" class="form-control" id="txEmailReset" placeholder="Enter email">
  </div>
  <a href="#" id="resetPassword" class="btn btn-success">Reset Password</a>
</div>

<script>
$(function() {
$("#resetPassword").click(function(){

		var request ={
				email: $("#txEmailReset").val()
			};
			$.when(
				$.ajax({
					url: "svc/resetpassword.php",
					type: "POST",
					data: request
				})
			).done(function(data) {
				
				
			});
			return false;
});
});
</script>



<?php renderFooter(); ?>